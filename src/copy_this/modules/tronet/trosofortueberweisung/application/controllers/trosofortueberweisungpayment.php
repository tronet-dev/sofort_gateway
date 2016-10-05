<?php
    /**
     * @file          trosofortueberweisungpayment.php
     * @link          http://www.tro.net
     * @copyright (C) tronet GmbH 2013
     * @package       modules
     * @addtogroup    controllers
     * @extend        payment
     */

    /**
     * Payment manager.
     * Customer payment manager class. Performs payment validation function, etc.
     *
     * NEW: deletes oxorder-object from DB
     */
    class trosofortueberweisungpayment extends trosofortueberweisungpayment_parent
    {
        /**
         * Currencies supported by SOFORT AG
         * @var array $_aCurrencies
         * @author tronet GmbH
         */
        protected $_aCurrencies = array(
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
         */
        public function validatePayment()
        {
            $sPaymentId = $this->getConfig()->getRequestParameter('paymentid');
            if ($sPaymentId == 'trosofortgateway_su')
            {
                // Determine current currency
                $sCurrency = $this->getConfig()->getActShopCurrencyObject()->name;
                if (!in_array($sCurrency, $this->_aCurrencies))
                {
                    // Show order step 3, so that the customer can switch payment type.
                    $oEx = oxNew('oxException');
                    $oEx->setMessage('ERROR_MESSAGE_CURRENCY');
                    oxRegistry::get("oxUtilsView")->addErrorToDisplay($oEx);

                    return 'payment';
                }
            }

            return parent::validatePayment();
        }

        /**
         * deleteOldOrder
         *
         * If a customer cancels payment process on SOFORT AG page or has been inactive for too long this method
         * will be triggered. The previous inserted order with the status "NOT_FINISHED" will be deleted.
         *
         * @author tronet GmbH
         */
        public function deleteOldOrder()
        {
            $sOrderId = $this->getSession()->getVariable('sess_challenge');
            $oOrder = oxNew('oxorder');
            $oOrder->load($sOrderId);
            $oOrder->troDeleteOldOrder();
        }
    }
