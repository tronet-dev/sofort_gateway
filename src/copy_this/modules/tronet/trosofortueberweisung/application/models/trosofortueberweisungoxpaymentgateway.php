<?php

    /**
     * @file      trosofortueberweisungoxpaymentgateway.php
     * @link      http://www.tro.net
     * @copyright (C) tronet GmbH 2017
     * @package   modules
     * @addtogroup models
     * @extend oxpaymentgateway
     */

    /**
     * Payment gateway manager.
     * Checks and sets payment method data, executes payment.
     *
     * NEW: redirects to SOFORT during paymentexecution
     */
    class trosofortueberweisungoxpaymentgateway extends trosofortueberweisungoxpaymentgateway_parent {

        /**
         * Executes payment gateway functionality.
         *
         * @extend executePayment
         *
         * @param double $dAmount order price
         * @param oxOrder $oOrder order object
         *
         * @return bool $mReturn
         */
        public function executePayment($dAmount, & $oOrder)
        {
            $oBasket = $this->getSession()->getBasket();
            $sPaymentId = $oBasket->getPaymentId();

            if ($sPaymentId == 'trosofortgateway_su')
            {
                $mReturn = $this->troExecutePayment($oOrder);
            }
            else
            {
                $mReturn = parent::executePayment($dAmount, $oOrder);
            }

            return $mReturn;
        }

        /**
         * _replaceUmlauts
         *
         * Replaces umlaut as not every bank supports them in transferal reasons.
         *
         * @param string $sString The string to process.
         *
         * @return string
         */
        private function _replaceUmlauts($sString)
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
         * troExecutePayment
         *
         * Creates an object of type Sofortueberweisung and stores data in it.
         *
         * The in shop configured language is not transmitted as of request by SOFORT AG.
         *
         * @param oxOrder $oOrder order object
         *
         * @return void
         */
        private function troExecutePayment($oOrder)
        {
            /*
             * Initialize
             */
            $oUser = $oOrder->getOrderUser();
            $oConfig = $this->getConfig();
            $oShop = $oConfig->getActiveShop();

            require_once( $oConfig->getModulesDir() . 'tronet/trosofortueberweisung/library/payment/sofortLibSofortueberweisung.inc.php');


            // Prepare data for Sofortueberweisung
            $aPlaceHolder = array(
                '[BSTNR]' => $oOrder->oxorder__oxordernr->value,
                '[KNR]'   => $oUser->oxuser__oxcustnr->value,
                '[KNAME]' => $oUser->oxuser__oxlname->value,
                '[DATUM]' => date('d.m.Y'),
                '[PRICE]' => round($oOrder->oxorder__oxtotalordersum->value, 2),
                '[SHP]'   => utf8_decode($oShop->oxshops__oxname->value),
            );

            $aPlaceHolderKeys = array_keys($aPlaceHolder);
            $aPlaceHolderValues = array_values($aPlaceHolder);

            $sGatewayReason1 = str_replace($aPlaceHolderKeys, $aPlaceHolderValues, $oConfig->getConfigParam('sTroGatewayReason'));
            $sGatewayReason2 = str_replace($aPlaceHolderKeys, $aPlaceHolderValues, $oConfig->getConfigParam('sTroGatewayReason2'));

            $aReason[0] = $this->_replaceUmlauts($sGatewayReason1);
            $aReason[1] = $this->_replaceUmlauts($sGatewayReason2);

            /*
             * Create new Sofortueberweisung and fill with data
             */
            $oSofort = new Sofortueberweisung($oConfig->getConfigParam('sTroGatewayConfKey'));
            $oSofort->setReason($aReason[0], $aReason[1]);

            $sUserCountryId = $oUser->oxuser__oxcountryid;
            $oCountry = oxNew('oxCountry');
            $oCountry->load($sUserCountryId);
            $oSofort->setSenderCountryCode($oCountry->oxcountry__oxisoalpha2);

            $oSofort->setAmount(round($oOrder->oxorder__oxtotalordersum->value, 2));

            // force_sid
            $sid = $oConfig->getRequestParameter('force_sid');
            if (empty($sid))
            {
                $sid = $_COOKIE['force_sid'];
            }
            if (empty($sid))
            {
                $sid = $_SESSION['force_sid'];
            }

            ########################################
            // Set urls
            $oSofort->setSuccessUrl($oConfig->getSslShopUrl().'?cl=order&fnc=continueExecute&transactionid=-TRANSACTION-&orderid='.$oOrder->oxorder__oxid->value.'&force_sid='.$sid);
            $oSofort->setAbortUrl  ($oConfig->getSslShopUrl().'?cl=payment&fnc=deleteOldOrder&force_sid='.$sid);
            $oSofort->setTimeoutUrl($oConfig->getSslShopUrl().'?cl=payment&fnc=deleteOldOrder&force_sid='.$sid);
            $oSofort->setNotificationUrl($oConfig->getSslShopUrl() . '?cl=trosofortueberweisung_notification');

            // Determine OXID eShop and trosofortueberweisung-Module-Version
            $aModuleVersions = $oConfig->getConfigParam('aModuleVersions');
            $sModuleVersion = 'oxid_' . $oConfig->getVersion() . '; trosu_' . $aModuleVersions['trosofortueberweisung'];
            $oSofort->setVersion($sModuleVersion);

            $oSofort->setEmailCustomer($oUser->oxuser__oxusername->value);

            $oSofort->setCurrencyCode($oConfig->getActShopCurrencyObject()->name);

            // After setting up an instance of Sofortueberweisung a request is send to the SOFORT API.
            // SOFORT API responses with a URL to which current customer is redirected to.
            $oSofort->sendRequest();
            $this->troRedirect($oSofort, $oOrder);
        }

        /**
         * checks for error and redirects to SOFORT or back to payment-view
         *
         * @param object $oSofort Sofortlib object
         * @param object $oOrder order object
         *
         * @return void
         */
        public function troRedirect($oSofort, $oOrder)
        {
            require_once( $this->getConfig()->getModulesDir() . 'tronet/trosofortueberweisung/library/core/sofortLibNotification.inc.php');

            if ($oSofort->isError())
            {
                // Data transmitted to SOFORT AG are invalid and cannot be processed by SOFORT AG.
                $oLang = oxRegistry::getLang();
                $sBaseLanguage = $oLang->getBaseLanguage();
                $sErrorMessage = $oLang->translateString('TRO_SOFORTGATEWAY_PAYMENTERROR', $sBaseLanguage);
                oxRegistry::get('oxutilsview')->addErrorToDisplay(
                    $sErrorMessage, false, true
                );

                // Add a log entry
                $aErrors = $oSofort->getErrors();
                if (is_array($aErrors))
                {
                    foreach ($aErrors as $aError)
                    {
                        $oEx = oxNew( "trosuexception", $aError['message'], $aError['code'], 'Payment-redirect to Sofort AG', $this->getConfig() );
                        $oEx->debugOut();
                    }
                }

                // Delete initiated order, show order step 3 (payment) and prompt him to choose an other payment
                // method.
                $this->sRedirUrl = $this->getConfig()->getSslShopUrl() . '?cl=payment&fnc=deleteOldOrder';
                oxRegistry::getUtils()->redirect($this->sRedirUrl);
            }
            else
            {
                // Data transmitted to SOFORT AG are valid. Save the Transaction-ID and redirect to SOFORT AG.
                $sTransactionId = $oSofort->getTransactionId();
                $oOrder->oxorder__oxtransid = new oxField($sTransactionId);
                $oOrder->save();
                $this->sRedirUrl = $oSofort->getPaymentUrl();
                oxRegistry::getUtils()->redirect($this->sRedirUrl);
            }
        }
    }
