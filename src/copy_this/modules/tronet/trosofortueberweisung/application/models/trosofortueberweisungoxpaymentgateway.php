<?php

    /**
     * Payment gateway manager.
     * Checks and sets payment method data, executes payment.
     *
     * @link          http://www.tro.net
     * @copyright (c) tronet GmbH 2018
     * @author        tronet GmbH
     *
     * @since         7.0.0
     * @version       7.0.3
     *
     * @property string $sRedirUrl
     */
    class trosofortueberweisungoxpaymentgateway extends trosofortueberweisungoxpaymentgateway_parent
    {
        /**
         * Executes payment gateway functionality.
         *
         * @extend executePayment
         *
         * @param double $dAmount order price
         * @param Order|TrosofortueberweisungOrder  $oOrder order object
         *
         * @return bool $mReturn
         * @since         7.0.0
         * @version       7.0.3
         */
        public function executePayment($dAmount, &$oOrder)
        {
            if ($this->_oPaymentInfo->oxuserpayments__oxpaymentsid->value == 'trosofortgateway_su')
            {
                // Important hint: redirect is executed
                $this->_troExecutePayment($oOrder);
            }
            
            return parent::executePayment($dAmount, $oOrder);
        }

        /**
         * _troExecutePayment
         *
         * Creates an object of type Sofortueberweisung and stores data in it.
         *
         * The in shop configured language is not transmitted as of request by SOFORT AG.
         *
         * At the end a redirect is performed.
         *
         * @param Order $oOrder order object
         *
         * @author  tronet GmbH
         * @since    7.0.3
         * @version  7.0.3
         */
        protected function _troExecutePayment(&$oOrder)
        {
            // Initialize
            $oUser = $oOrder->getOrderUser();
            $oConfig = $this->getConfig();
            $oActiveShop = $oConfig->getActiveShop();

            require_once( $oConfig->getModulesDir() . 'tronet/trosofortueberweisung/library/payment/sofortLibSofortueberweisung.inc.php');

            // Prepare data for Sofortueberweisung
            $sOrderTotalSumString = (string) number_format($oOrder->oxorder__oxtotalordersum->value, 2, ',', '');
            $aTransactionPlaceholder = array(
                '[BSTNR]' => $oOrder->oxorder__oxordernr->value,
                '[KNR]'   => $oUser->oxuser__oxcustnr->value,
                '[KNAME]' => $oUser->oxuser__oxlname->value,
                '[DATUM]' => date('d.m.Y'),
                '[PRICE]' => $sOrderTotalSumString,
                '[SHP]'   => utf8_decode($oActiveShop->oxshops__oxname->value),
            );

            $aTransactionPlaceholderKeys = array_keys($aTransactionPlaceholder);
            $aTransactionPlaceholderValues = array_values($aTransactionPlaceholder);

            $sGatewayReason1 = str_replace($aTransactionPlaceholderKeys, $aTransactionPlaceholderValues, $oConfig->getConfigParam('sTroGatewayReason'));
            $sGatewayReason2 = str_replace($aTransactionPlaceholderKeys, $aTransactionPlaceholderValues, $oConfig->getConfigParam('sTroGatewayReason2'));

            $sGatewayReason1 = $this->_troReplaceUmlauts($sGatewayReason1);
            $sGatewayReason2 = $this->_troReplaceUmlauts($sGatewayReason2);

            // Force max gateway length.
            $sGatewayReason1 = substr($sGatewayReason1, 0, 27);
            $sGatewayReason2 = substr($sGatewayReason2, 0, 27);

            // Create new Sofortueberweisung and fill with data
            $oSofortueberweisung = new Sofortueberweisung($oConfig->getConfigParam('sTroGatewayConfKey'));
            $oSofortueberweisung->setReason($sGatewayReason1, $sGatewayReason2);

            $sUserCountryId = $oUser->oxuser__oxcountryid;
            $oCountry = oxNew('oxCountry');
            $oCountry->load($sUserCountryId);
            $oSofortueberweisung->setSenderCountryCode($oCountry->oxcountry__oxisoalpha2);

            $totalOrderSumAmount = round($oOrder->oxorder__oxtotalordersum->value, 2);
            $oSofortueberweisung->setAmount($totalOrderSumAmount);

            // force_sid
            $force_sid = $oConfig->getRequestParameter('force_sid');
            if (empty($force_sid))
            {
                $force_sid = $_COOKIE['force_sid'];
            }
            if (empty($force_sid))
            {
                $force_sid = $_SESSION['force_sid'];
            }

            ########################################
            // Set urls
            $oSofortueberweisung->setSuccessUrl($oConfig->getSslShopUrl().'?cl=order&fnc=troContinueExecute&transactionid=-TRANSACTION-&orderid='.$oOrder->oxorder__oxid->value.'&force_sid='.$force_sid);
            $oSofortueberweisung->setAbortUrl($oConfig->getSslShopUrl().'?cl=payment&fnc=troDeleteOldOrder&force_sid='.$force_sid);
            $oSofortueberweisung->setTimeoutUrl($oConfig->getSslShopUrl().'?cl=payment&fnc=troDeleteOldOrder&force_sid='.$force_sid);
            $oSofortueberweisung->setNotificationUrl($oConfig->getSslShopUrl().'?cl=trosofortueberweisung_notification');

            // Determine OXID eShop and trosofortueberweisung-Module-Version
            $aModuleVersions = $oConfig->getConfigParam('aModuleVersions');
            $sModuleVersion = 'oxid_'.$oConfig->getVersion().'; trosu_'.$aModuleVersions['trosofortueberweisung'];
            $oSofortueberweisung->setVersion($sModuleVersion);

            $oSofortueberweisung->setEmailCustomer($oUser->oxuser__oxusername->value);

            $oSofortueberweisung->setCurrencyCode($oConfig->getActShopCurrencyObject()->name);

            // After setting up an instance of Sofortueberweisung a request is send to the SOFORT API.
            // SOFORT API responses with a URL to which current customer is redirected to.
            $oSofortueberweisung->sendRequest();
            $this->_troRedirect($oSofortueberweisung, $oOrder);
        }

        /**
         * _troReplaceUmlauts
         *
         * Replaces umlaut as not every bank supports them in transferal reasons.
         *
         * @param string $sString The string to process.
         *
         * @return string
         * 
         * @author  tronet GmbH
         * @since    7.0.0
         * @version  7.0.0
         */
        protected function _troReplaceUmlauts($sString)
        {
            $aSearch = array(
                chr(192), chr(193), chr(194), chr(195), chr(196), chr(197), #A
                chr(198), #AE
                chr(199), #C
                chr(200), chr(201), chr(202), chr(203), #E
                chr(204), chr(205), chr(206), chr(207), #I
                chr(208), #D
                chr(209),
                chr(210), chr(211), chr(212), chr(213), chr(214), chr(216), #O
                chr(217), chr(218), chr(219), chr(220), #U
                chr(221), #Y
                chr(223), #ss
                chr(224), chr(225), chr(226), chr(227), chr(228), chr(229), #a
                chr(230), #ae
                chr(231), #c
                chr(232), chr(233), chr(234), chr(235), #e
                chr(236), chr(237), chr(238), chr(239), #i
                chr(240), #d
                chr(241), #n
                chr(242), chr(243), chr(244), chr(245), chr(246), chr(248), #o
                chr(249), chr(250), chr(251), chr(252), #u
                chr(253), chr(255), #y
                chr(39), "&#039;", #'
            );
            $aReplace = array(
                'A', 'A', 'A', 'A', 'Ae', 'A',
                'Ae',
                'C',
                'E', 'E', 'E', 'E',
                'I', 'I', 'I', 'I',
                'D',
                'N',
                'O', 'O', 'O', 'O', 'Oe', 'O',
                'U', 'U', 'U', 'Ue',
                'Y',
                'ss',
                'a', 'a', 'a', 'a', 'ae', 'a',
                'ae',
                'c',
                'e', 'e', 'e', 'e',
                'i', 'i', 'i', 'i',
                'd',
                'n',
                'o', 'o', 'o', 'o', 'oe', 'o',
                'u', 'u', 'u', 'ue',
                'y', 'y',
                '', '',
            );
            return str_replace($aSearch, $aReplace, $sString);
        }

        /**
         * checks for error and redirects to SOFORT or back to payment-view
         *
         * @param Sofortueberweisung $oSofortueberweisung
         * @param Order              $oOrder order object
         *
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 7.0.3
         */
        protected function _troRedirect($oSofortueberweisung, &$oOrder)
        {
            require_once($this->getConfig()->getModulesDir().'tronet/trosofortueberweisung/library/core/sofortLibNotification.inc.php');

            if ($oSofortueberweisung->isError())
            {
                // Data transmitted to SOFORT AG are invalid and cannot be processed by SOFORT AG.
                $oLang = oxRegistry::getLang();
                $sBaseLanguage = $oLang->getBaseLanguage();
                $sErrorMessage = $oLang->translateString('TRO_SOFORTGATEWAY_PAYMENTERROR', $sBaseLanguage);
                oxRegistry::get('oxutilsview')->addErrorToDisplay($sErrorMessage, false, true);

                // Add a log entry
                $aErrors = $oSofortueberweisung->getErrors();
                if (is_array($aErrors))
                {
                    foreach ($aErrors as $aErrorDetails)
                    {
                        $oSofortException = oxNew("trosuexception", $aErrorDetails['message'], $aErrorDetails['code'], 'Payment-redirect to Sofort AG', $this->getConfig());
                        $oSofortException->debugOut();
                    }
                }

                // Delete initiated order, show order step 3 (payment) and prompt him to choose an other payment
                // method.
                $this->sRedirUrl = $this->getConfig()->getSslShopUrl() . '?cl=payment&fnc=troDeleteOldOrder';
                oxRegistry::getUtils()->redirect($this->sRedirUrl);
            }
            else
            {
                // Data transmitted to SOFORT AG are valid. Save the Transaction-ID and redirect to SOFORT AG.
                $sTransactionId = $oSofortueberweisung->getTransactionId();
                $oOrder->oxorder__oxtransid = new oxField($sTransactionId);
                $oOrder->save();
                $this->sRedirUrl = $oSofortueberweisung->getPaymentUrl();
                oxRegistry::getUtils()->redirect($this->sRedirUrl);
            }
        }
    }
