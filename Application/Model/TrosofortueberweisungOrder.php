<?php

namespace Tronet\Trosofortueberweisung\Application\Model;

use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Application\Model\UserPayment;
use OxidEsales\Eshop\Application\Model\Payment;
use OxidEsales\Eshop\Core\Counter;
use OxidEsales\Eshop\Core\Database\Adapter\DatabaseInterface;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Session;
use OxidEsales\Eshop\Core\UtilsObject;
use OxidEsales\Eshop\Core\Model\BaseModel;

/**
 * Order manager.
 * Performs creation assigning, updating, deleting and other order functions.
 *
 * NEW: sets ordernr before finalizing and continues finalizing, if stopped during paymentexecution
 *
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2017
 * @author        tronet GmbH
 *
 * @since         7.0.0
 * @version       8.0.0
 *
 * @property Field $oxorder__oxordernr
 * @property Field $oxorder__oxstorno
 * @property Field $oxorder__oxorderdate
 */
class TrosofortueberweisungOrder extends TrosofortueberweisungOrder_parent
{
    /**
     * @var string $_troPaymentStatus
     * 
     * @author  tronet GmbH
     * @since   8.0.0
     * @version 8.0.0
     */
    protected $_sTroPaymentStatus = null;
        
    /**
     * gets Order Basket after returning from Sofort.
     *
     * @author  tronet GmbH
     * @since   8.0.0
     * @version 8.0.0
     */
    public function getTroOrderBasket($blStockCheck = true)
    {
        $oBasket = $this->_getOrderBasket($blStockCheck);
        $this->_addOrderArticlesToBasket($oBasket, $this->getOrderArticles(true));
        $oBasket->calculateBasket(true);

        return $oBasket;
    }

    /**
     * Executes payment. Additionally loads oxPaymentGateway object, initiates
     * it by adding payment parameters (oxPaymentGateway::setPaymentParams())
     * and finally executes it (oxPaymentGateway::executePayment()). On failure -
     * deletes order and returns * error code 2.
     *
     * @param Basket        $oBasket      basket object
     * @param UserPayment   $oUserPayment user payment object
     *
     * @return integer 2 or an error code
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    protected function _executePayment(Basket $oBasket, $oUserPayment)
    {
        if ($oBasket->getPaymentId() === 'trosofortgateway_su')
        {
            // Safe current order only ($this) and the current basket ($oBasket) in the session,
            // so that the info are available when the user returns from the SOFORT AG.
            $oSession = Registry::getSession();

            if (!$this->oxorder__oxordernr->value)
            {
                $this->_setNumber();
            }

            if (!$this->oxorder__oxorderdate->value)
            {
                $this->_updateOrderDate();
            }

            $oOrder = clone $this;
            $oSession->setVariable('trosuoxorder', $oOrder);
            $oSession->setVariable('trosubasket', $oBasket);
        }

        return parent::_executePayment($oBasket, $oUserPayment);
    }

    /**
     * bug fix: if a user uses the browser back button to return to the shop and submits order again
     * the order does not have an order date right away - only after order is finished an order date
     * is given.
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function troUpdateOrderDate()
    {
        # We need to empty skip save fields as orderdate is in this array
        $this->_aSkipSaveFields = [];
        $this->_updateOrderDate();
    }

    /**
     * troContinueFinalizeOrder
     *
     * On return from SOFORT AG OXIDs core method finalizeOrder is continued.
     *
     * @param Basket $oBasket             Shopping basket object
     * @param User   $oUser               Current user object
     *
     * @return string
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function troContinueFinalizeOrder(Basket $oBasket, $oUser)
    {
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->startTransaction();
        try {
            // payment information
            $oUserPayment = $this->_setPayment($oBasket->getPaymentId());

            //////////////////////////////////
            // Rest of original finalizeOrder

            if (!$this->oxorder__oxordernr->value)
            {
                $this->_setNumber();
            }
            else
            {
                oxNew(\OxidEsales\Eshop\Core\Counter::class)->update($this->_getCounterIdent(), $this->oxorder__oxordernr->value);
            }

            // deleting remark info only when order is finished
            \OxidEsales\Eshop\Core\Registry::getSession()->deleteVariable('ordrem');

            //#4005: Order creation time is not updated when order processing is complete
            $this->_updateOrderDate();

            // updating order trans status (success status)
            $this->_setOrderStatus('OK');

            // store orderid
            $oBasket->setOrderId($this->getId());

            // updating wish lists
            $this->_updateWishlist($oBasket->getContents(), $oUser);

            // updating users notice list
            $this->_updateNoticeList($oBasket->getContents(), $oUser);

            // marking vouchers as used and sets them to $this->_aVoucherList (will be used in order email)
            // skipping this action in case of order recalculation
            $this->_markVouchers($oBasket, $oUser);

            // send order by email to shop owner and current user
            // skipping this action in case of order recalculation
            $iRet = $this->_sendOrderByEmail($oUser, $oBasket, $oUserPayment);

            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->commitTransaction();
        } catch (Exception $exception) {
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->rollbackTransaction();

            throw $exception;
        }

        // Call the original finalizeOrder method. In case the method is extended by other modules,
        // this makes sure those features are executed as well.
        $iRet2 = $this->finalizeOrder($oBasket, $oUser, false);

        // Return value of the original finalizeOrder should be self::ORDER_STATE_ORDEREXISTS in any case,
        // as the order has been created before the redirection to SOFORT AG.
        // In case a different value is returned, we pass this return value.
        if ($iRet2 == self::ORDER_STATE_ORDEREXISTS)
        {
            return $iRet;
        }

        return $iRet2;
    }

    /**
     * Order checking, processing and saving method.
     * Before saving performed checking if order is still not executed (checks in
     * database oxorder table for order with know ID), if yes - returns error code 3,
     * if not - loads payment data, assigns all info from basket to new oxorder object
     * and saves full order with error status. Then executes payment. On failure -
     * deletes order and returns error code 2. On success - saves order (oxorder::save()),
     * removes article from wishlist (oxorder::_updateWishlist()), updates voucher data
     * (oxorder::_markVouchers()). Finally sends order confirmation email to customer
     * (oxemail::SendOrderEMailToUser()) and shop owner (oxemail::SendOrderEMailToOwner()).
     * If this is order recalculation, skipping payment execution, marking vouchers as used
     * and sending order by email to shop owner and user
     * Mailing status (1 if OK, 0 on error) is returned.
     *
     * @param Basket $oBasket             Shopping basket object
     * @param User   $oUser               Current user object
     * @param bool   $blRecalculatingOrder Order recalculation
     *
     * @return integer
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function finalizeOrder(Basket $oBasket, $oUser, $blRecalculatingOrder = false)
    {
        $this->troDeleteOldOrder();

        return parent::finalizeOrder($oBasket, $oUser, $blRecalculatingOrder);
    }

    /**
     * Delete order with current ID.
     *
     * An order with this ID may exists if user has been redirected to SOFORT payment process
     * and user has used the browsers-back-button to get back to the eShop.
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function troDeleteOldOrder()
    {
        $oSession = Registry::getSession();
        $sOrderId = $oSession->getVariable('sess_challenge');

        if ($sOrderId)
        {
            $oDatabaseProvider = DatabaseProvider::getDb();
            $sSqlSelect = "select oxid from oxorder 
                  where oxpaymenttype = 'trosofortgateway_su' 
                  and oxtransstatus = 'NOT_FINISHED'
                  and oxid = " . $oDatabaseProvider->quote($sOrderId);

            if ($oDatabaseProvider->getOne($sSqlSelect))
            {
                // check what should happen, depending on the users choice. Delete or cancel the old order
                // 0 => cancel order
                // 1 => delete order
                $iCancelOrderMode = $this->getConfig()->getConfigParam('iTroGatewayCanceledOrders');

                if ($iCancelOrderMode == 0)
                {
                    $this->load($sOrderId);
                    $this->cancelOrder();
                    $this->oxorder__oxordernr = null;
                    $this->oxorder__oxstorno = null;
                    $oSession->setVariable('sess_challenge', UtilsObject::getInstance()->generateUId());
                }
                elseif ($iCancelOrderMode == 1)
                {
                    $this->delete($sOrderId);
                    $oSession->setVariable('sess_challenge', UtilsObject::getInstance()->generateUId());
                }
            }
        }
    }
    
    /**
     * returns name of the payment-method used for this order
     *
     * @return string
     * 
     * @author tronet GmbH
     * @since   8.0.0
     * @version 8.0.0
     */
    public function getTroPaymentName()
    {
        if ($this->_oPayment === null)
        {
            $this->_oPayment = oxNew(Payment::class);
            $this->_oPayment->loadinlang(\OxidEsales\Eshop\Core\Registry::getLang(), $this->oxorder__oxpaymenttype->value);
        }

        return $this->_oPayment->oxpayments__oxdesc->value;
    }        
    
    /**
     * returns current paymentstatus from DB-table trogatewaylog
     *
     * @return string
     * 
     * @author tronet GmbH
     * @since   8.0.0
     * @version 8.0.0
     */
    public function getTroPaymentStatus()
    {
        if ($this->_sTroPaymentStatus === null)
        {
            if ($this->getPaymentType()->oxuserpayments__oxpaymentsid->rawValue === 'trosofortgateway_su')
            {
                $oDatabaseProvider = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
                $sSqlSelect = "SELECT status FROM trogatewaylog WHERE transactionid='" . $this->oxorder__oxtransid->value . "' ORDER BY timestamp DESC LIMIT 1";
                $this->_sTroPaymentStatus = $oDatabaseProvider->getOne($sSqlSelect);
            }
        }

        return $this->_sTroPaymentStatus;
    }
}
