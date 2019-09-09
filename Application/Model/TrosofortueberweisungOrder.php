<?php

namespace Tronet\Trosofortueberweisung\Application\Model;

use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Application\Model\OrderArticle;
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
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2018
 * @author        tronet GmbH
 *
 * @since         8.0.0
 * @version       8.0.7
 */
class TrosofortueberweisungOrder extends TrosofortueberweisungOrder_parent
{
    /**
     * @var string $_sTroPaymentStatus
     * 
     * @author  tronet GmbH
     * @since   8.0.0
     * @version 8.0.0
     */
    protected $_sTroPaymentStatus = null;

    /**
     * @var string $_blContinueFinalizeOrder
     * 
     * @author  tronet GmbH
     * @since   8.0.1
     * @version 8.0.1
     */
    protected $_blContinueFinalizeOrder = false;

    /**
     * @var string $_sOrderStatus
     * 
     * @author  tronet GmbH
     * @since   8.0.1
     * @version 8.0.1
     */
    protected $_sOrderStatus = null;

    /**
     * gets Order Basket after returning from Sofort.
     *
     * @author  tronet GmbH
     * @since   8.0.0
     * @version 8.0.6
     */
    public function getTroOrderBasket()
    {
        $oBasket = $this->_getOrderBasket();
        $this->_oArticles = null;
        
        $this->_addOrderArticlesToBasket($oBasket, $this->getOrderArticles(true));
        $oBasket->calculateBasket(true);

        return $oBasket;
    }

    /**
     * @param Basket        $oBasket      basket object
     * @param UserPayment   $oUserPayment user payment object
     *
     * @return integer 2 or an error code
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.1
     */
    protected function _executePayment(Basket $oBasket, $oUserPayment)
    {
        $sPaymentId = $oBasket->getPaymentId();
        if ($sPaymentId == 'trosofortgateway_su')
        {
            // Setze schon jetzt eine Bestellnummer, 
            // um sie als Verwendungszweck der Überweisung nutzen zu können
            if (!$this->oxorder__oxordernr->value)
            {
                $this->_setNumber();
            }

            // Setze Bestelldatum
            $this->_updateOrderDate();

            // verwendete Gutscheine als benutzt markieren, da beim Bestellabschluss
            // über die SOFORT-Notification keine Session-Variable mit den verwendeten
            // Gutscheinen zur Verfügung steht.
            $oUser = $this->getOrderUser();
            $this->_markVouchers($oBasket, $oUser);
        }

        return parent::_executePayment($oBasket, $oUserPayment);
    }

    /**
     * @author  tronet GmbH
     * @since   8.0.1
     * @version 8.0.1
     */
    protected function _updateOrderDate()
    {
        if (!$this->oxorder__oxorderdate->value || $this->oxorder__oxorderdate->value == '0000-00-00 00:00:00')
        {
            parent::_updateOrderDate();
        }
    }

    /**
     * @param Basket $oBasket             Shopping basket object
     * @param User   $oUser               Current user object
     * @param bool   $blRecalculatingOrder Order recalculation
     *
     * @return integer
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.6
     */
    public function finalizeOrder(Basket $oBasket, $oUser, $blRecalculatingOrder = false)
    {
        $this->_troHandleExistingSOFORTOrder();

        // Bei Sofort-Überweisungsbestellungen Basket aus der Session in die Datenbank schreiben
        $sPaymentId = $oBasket->getPaymentId();
        if ($sPaymentId == 'trosofortgateway_su' && !$this->_blContinueFinalizeOrder)
        {
            $this->oxorder__trousersession = new Field(serialize($_SESSION));
        }

        return parent::finalizeOrder($oBasket, $oUser, $blRecalculatingOrder);
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
     * @version 8.0.7
     */
    public function troContinueFinalizeOrder(Basket $oBasket, $oUser)
    {
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->startTransaction();
        try {
            // payment information
            $oUserPayment = $this->_setPayment($oBasket->getPaymentId());
            $this->_blContinueFinalizeOrder = true;
            Registry::getLang()->setBaseLanguage($this->getOrderLanguage());

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

            // Wenn die Routine ueber die Notification-URL aufgerufen wird,
            // muessen sich die Funktionen verhalten, als ob man im Backend waere.
            // Der E-Mail Versand muss allerdings im "Frontend" stattfinden,
            // weil sonst die Sprachvariablen u.U. nicht richtig gefunden werden
            $blAdmin = $this->isAdmin();
            $this->setAdminMode(false);

            // send order by email to shop owner and current user
            // skipping this action in case of order recalculation
            $iRet = $this->_sendOrderByEmail($oUser, $oBasket, $oUserPayment);

            $this->setAdminMode($blAdmin);

            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->commitTransaction();
        } catch (Exception $exception) {
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->rollbackTransaction();

            throw $exception;
        }

        // End Rest of original finalizeOrder
        //////////////////////////////////

        $this->oxorder__trousersession = null;
        $this->save();

        // Reset: Lade nun oxorderarticles statt oxarticles in das oxbasket-Objekt
        // Dies wird von finalizeOrder erwartet, wenn $blRecalculatingOrder = true
        $oBasket = $this->getTroOrderBasket();

        // Call the original finalizeOrder method. In case the method is extended by other modules,
        // this makes sure those features are executed as well.
        $iRet2 = $this->finalizeOrder($oBasket, $oUser, true);

        // Wenn die original finalizeOrder nicht ok liefert, gebe deren Wert zurück
        if ($iRet2 != self::ORDER_STATE_OK)
        {
            return $iRet2;
        }
        return $iRet;
    }

    /**
     * Liefere den Status einer Sofortüberweisungs-Bestellung
     * 
     * @author  tronet GmbH
     * @since   8.0.1
     * @version 8.0.1
     */
    protected function _getTroSOFORTOrderStatus($sOrderId)
    {
        if ($this->_sOrderStatus === null)
        {
            $oDb = DatabaseProvider::getDb();
            $sSql = "SELECT oxtransstatus FROM oxorder
                  WHERE oxpaymenttype = 'trosofortgateway_su'
                  AND oxid = ".$oDb->quote($sOrderId);
            $this->_sOrderStatus = $oDb->getOne($sSql);
        }
        return $this->_sOrderStatus;
    }

    /**
     * Wenn nach der Bezahlung auf Seiten der SOFORT AG 
     * nicht wieder in den Shop zurückgekehrt wird,
     * bleibt die Bestellung in der Session bestehen 
     * und muss gesondert behandelt werden
     * 
     * @author  tronet GmbH
     * @since   8.0.1
     * @version 8.0.1
     */
    protected function _troHandleExistingSOFORTOrder()
    {
        if ($this->_blContinueFinalizeOrder)
        {
            // finalizeOrder wurde von troContinueFinalizeOrder aufgerufen
            // angefangene Sofortueberweisungsbestellung wird fortgesetzt
            // hier ist nichts zu tun
            return;
        }

        $oSession = Registry::getSession();
        $sOrderId = $oSession->getVariable('sess_challenge');

        $sOrderStatus = $this->_getTroSOFORTOrderStatus($sOrderId);
        if ($sOrderStatus == 'NOT_FINISHED')
        {
            // angefangene Sofortueberweisungsbestellung
            // storniere bisherige Artikel
            // behalte oxorder und führe sie zu Ende
            $this->load($sOrderId);
            $oOrderArticles = $this->getOrderArticles(false);
            foreach ($oOrderArticles as $oOrderArticle)
            {
                // die Funktion oxoderarticles->delete berücksichtigt den Lagerbestand
                $oOrderArticle->delete();
            }
        }
        elseif ($sOrderStatus == 'OK')
        {
            // beendete Sofortueberweisungsbestellung -> neue ID vergeben
            $oSession->setVariable('sess_challenge', UtilsObject::getInstance()->generateUID());
        }
    }

    /**
     * überladene Oxid-Funktion
     * 
     * @author  tronet GmbH
     * @since   8.0.1
     * @version 8.0.1
     */
    protected function _checkOrderExist($sOxId = null)
    {
        $sOrderStatus = $this->_getTroSOFORTOrderStatus($sOxId);
        if ($this->_blContinueFinalizeOrder || $sOrderStatus == 'NOT_FINISHED')
        {
            // angefangene Sofortueberweisungsbestellung -> führe sie zu Ende,
            // als ob diese Bestellung noch nicht existiert
            return false;
        }
        else
        {
            // andere Bestellung
            return parent::_checkOrderExist($sOxId);
        }
    }

    /**
     * Bestellung mit dieser ID loeschen
     * Bestellung mit dieser ID kann existieren, wenn vorher schon mal zu SofortUeberweisung
     * weitergeleitet wurde und ueber Back-Button des Browsers zurueckgekehrt wurde
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function troDeleteOldOrder()
    {
        if ($this->oxorder__oxpaymenttype->value == 'trosofortgateway_su'
         && $this->oxorder__oxtransstatus->value == 'NOT_FINISHED'
         && $this->oxorder__oxstorno->value == 0)
        {
            // check what should happen, depending on the users choice. Delete or cancel the old order
            // 0 => cancel order
            // 1 => delete order
            $iCancelOrderMode = Registry::getConfig()->getConfigParam('iTroGatewayCanceledOrders');
            $oSession = Registry::getSession();

            if ($iCancelOrderMode == 0)
            {
                $this->cancelOrder();
                $this->oxorder__oxordernr = null;
                $this->oxorder__oxstorno = null;
                $oSession->setVariable('sess_challenge', UtilsObject::getInstance()->generateUId());
            }
            elseif ($iCancelOrderMode == 1)
            {
                $this->delete();
                $oSession->setVariable('sess_challenge', UtilsObject::getInstance()->generateUId());
            }

            // Gutscheine mussten bereits vor Bezahlung in der Funktion _executePayment
            // als eingelöst markiert werden.
            // Bei Bestellabbruch muss dies rückgängig gemacht werden.
            $this->_troMarkVouchersAsUnused();
        }
    }

    /**
    * @author  tronet GmbH
    * @since   8.0.6
    * @version 8.0.6
    */
    public function cancelOrder()
    {
        $this->oxorder__trousersession = null;
        return parent::cancelOrder();
    }

    /**
     * Gutscheine mussten bereits vor Bezahlung in der Funktion _executePayment
     * als eingelöst markiert werden.
     * Bei Bestellabbruch muss dies rückgängig gemacht werden.
     *
     * @author  tronet GmbH
     * @since   8.0.1
     * @version 8.0.1
     */
    protected function _troMarkVouchersAsUnused()
    {
        $oDb = DatabaseProvider::getDb();
        $sUpdate = "UPDATE oxvouchers SET oxdateused = 0, oxorderid = '', oxuserid = '' WHERE oxorderid = '".$this->getId()."'";
        $oDb->execute($sUpdate);
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
            $this->_oPayment->loadinlang(\OxidEsales\Eshop\Core\Registry::getLang()->getObjectTplLanguage(), $this->oxorder__oxpaymenttype->value);
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
            if ($this->getPaymentType()->oxuserpayments__oxpaymentsid->rawValue == 'trosofortgateway_su')
            {
                $oDb = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
                $sSelect = "SELECT status FROM trogatewaylog WHERE transactionid='" . $this->oxorder__oxtransid->value . "' ORDER BY timestamp DESC LIMIT 1";
                $this->_sTroPaymentStatus = $oDb->getOne($sSelect);
            }
        }

        return $this->_sTroPaymentStatus;
    }
}
