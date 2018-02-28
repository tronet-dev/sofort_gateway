<?php
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
     * @version       7.0.3
     */
    class trosofortueberweisungpayment extends trosofortueberweisungpayment_parent
    {
        /**
         * Currencies supported by SOFORT AG
         * @var array $_aSupportedCurrencies
         * 
         * @author        tronet GmbH
         * @since         7.0.3
         * @version       7.0.3
         */
        protected $_aSupportedCurrencies = array(
            'EUR',
            'CHF',
            'GBP',
            'PLN',
            'HUF',
            'CZK',
        );

        /**
         * validatePayment checks whether current currency is supported by SOFORT AG.
         *
         * Is triggered by oxOrder::validateOrder. Named method is triggered by oxOrder::finalizeOrder
         *
         * @return string
         * 
         * @author  tronet GmbH
         * @since         7.0.0
         * @version       7.0.3
         */
        public function validatePayment()
        {
            $sPaymentId = $this->getConfig()->getRequestParameter('paymentid');
            if ($sPaymentId == 'trosofortgateway_su')
            {
                // Determine current currency
                $sCurrency = $this->getConfig()->getActShopCurrencyObject()->name;
                if (!in_array($sCurrency, $this->_aSupportedCurrencies))
                {
                    // Show order step 3, so that the customer can switch payment type.
                    $oStandardException = oxNew('oxException');
                    $oStandardException->setMessage('ERROR_MESSAGE_CURRENCY');
                    oxRegistry::get("oxUtilsView")->addErrorToDisplay($oStandardException);

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
         * @since         7.0.3
         * @version       7.0.3
         */
        public function troDeleteOldOrder()
        {
            $sOrderId = $this->getSession()->getVariable('sess_challenge');
            $oOrder = oxNew('oxorder');
            $oOrder->load($sOrderId);
            $oOrder->troDeleteOldOrder();
        }
    }
