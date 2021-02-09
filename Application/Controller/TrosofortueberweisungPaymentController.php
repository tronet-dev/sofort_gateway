<?php

namespace Tronet\Trosofortueberweisung\Application\Controller;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Exception\StandardException;
use OxidEsales\Eshop\Core\UtilsView;

/**
 * Payment manager.
 * Customer payment manager class. Performs payment validation function, etc.
 *
 * NEW: deletes oxorder-object from DB
 *
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2018
 * @author        tronet GmbH
 *
 * @since         7.0.0
 * @version       8.0.0
 */
class TrosofortueberweisungPaymentController extends TrosofortueberweisungPaymentController_parent
{
    /**
     * Currencies supported by SOFORT AG
     * @var array $_aSupportedCurrencies
     * 
     * @author        tronet GmbH
     * @since         7.0.0
     * @version       8.0.0
     */
    protected $_aSupportedCurrencies = [
        'EUR',
        'CHF',
        'GBP',
        'PLN',
        'HUF',
        'CZK',
    ];

    /**
     * validatePayment checks whether current currency is supported by SOFORT AG.
     *
     * Is triggered by oxOrder::validateOrder. Named method is triggered by oxOrder::finalizeOrder
     *
     * @return string
     * 
     * @author  tronet GmbH
     * @since         7.0.0
     * @version       8.0.0
     */
    public function validatePayment()
    {
        $sPaymentId = Registry::getConfig()->getRequestParameter('paymentid');
        if ($sPaymentId == 'trosofortgateway_su')
        {
            // Determine current currency
            $sCurrency = Registry::getConfig()->getActShopCurrencyObject()->name;
            if (!in_array($sCurrency, $this->_aSupportedCurrencies))
            {
                // Show order step 3, so that the customer can switch payment type.
                $oStandardException = oxNew(StandardException::class);
                $oStandardException->setMessage('ERROR_MESSAGE_CURRENCY');
                Registry::get(UtilsView::class)->addErrorToDisplay($oStandardException);

                return 'payment';
            }
        }

        return parent::validatePayment();
    }

    /**
     * troDeleteOldOrder
     *
     * If a customer cancels payment process on SOFORT AG page or has been inactive for too long this method
     * will be triggered. The previous inserted order with the status "NOT_FINISHED" will be deleted.
     *
     * @author        tronet GmbH
     * @since         7.0.0
     * @version       8.0.0
     */
    public function troDeleteOldOrder()
    {
        $sOrderId = $this->getSession()->getVariable('sess_challenge');
        $oOrder = oxNew(Order::class);
        $oOrder->load($sOrderId);
        $oOrder->troDeleteOldOrder();
    }
}
