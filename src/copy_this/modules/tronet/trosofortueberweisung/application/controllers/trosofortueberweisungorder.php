<?php
    /**
     * @file          trosofortueberweisungorder.php
     * @link          http://www.tro.net
     * @copyright (C) tronet GmbH 2014
     * @package       modules
     * @addtogroup    controllers
     * @extend        order
     */

    /**
     * Order manager. Arranges user ordering data, checks/validates
     * it, on success stores ordering data to DB.
     *
     * NEW: sets ordernr before finalizing and continues finalizing, if stopped during paymentexecution
     */
    class trosofortueberweisungorder extends trosofortueberweisungorder_parent
    {
        /**
         * Finalizes order, if we stopped during executePayment
         *
         * @return string
         */
        public function continueExecute()
        {
            $oSession = new oxSession();

            // In order to avoid abuse we first verify that a previously initiated order has been payed.
            // In case the test fails for any reason order step 3 will be displayed.
            if (!$this->_continueExecuteSUOrder())
            {
                return 'payment';
            }

            /*
             * As this function is triggered due to external processes variables $oUser, $oBasket, $oOrder have
             * to be initialized.
             */

            // additional check if we really really have a user now
            if (!$oUser = $this->getUser())
            {
                return 'user';
            }

            // get basket contents
            $oBasket = $oSession->getVariable('trosubasket');
            $oBasket->troGetFromSession();
            if ($oBasket->getProductsCount())
            {

                try
                {
                    $oOrder = $oSession->getVariable('trosuoxorder');

                    // Finish oxOrder::finalizeOrder
                    $iSuccess = $oOrder->continueFinalizeOrder($oBasket, $oUser);

                    ////////////////////////////////////////////////////////////
                    //// Following is the rest of oxOrder::execute

                    // performing special actions after user finishes order (assignment to special user groups)
                    $oUser->onOrderExecute($oBasket, $iSuccess);

                    // proceeding to next view
                    return $this->_getNextStep($iSuccess);
                }
                catch (oxOutOfStockException $oEx)
                {
                    oxRegistry::get("oxUtilsView")->addErrorToDisplay($oEx, false, true, 'basket');
                }
                catch (oxNoArticleException $oEx)
                {
                    oxRegistry::get("oxUtilsView")->addErrorToDisplay($oEx);
                }
                catch (oxArticleInputException $oEx)
                {
                    oxRegistry::get("oxUtilsView")->addErrorToDisplay($oEx);
                }
            }
        }

        /**
         * _continueExecuteSUOrder
         *
         * Verifies previously initiated order has been payed.
         *
         * The success link contains the Transaction-ID.
         *
         * This function sends a new request to SOFORT AG regarding the current transaction status.
         *
         * If the current transaction status is either "pending", "received" or "untraceable" plus table oxOrder
         * contains a record with the Transaction-ID which has not been finished yet (status: NOT_FINISHED) plus
         * the defined payment method is "trosofortgateway_su" the order processing will be continued.
         *
         * We assume order should not be continued. If everything is ok the return var $blContinueExecuteSUOrder is
         * set to true.
         *
         * @return boolean
         * @author tronet GmbH
         */
        private function _continueExecuteSUOrder()
        {
            $blContinueExecuteSUOrder = false;

            $oConfig = $this->getConfig();
            require_once($oConfig->getModulesDir() . 'tronet/trosofortueberweisung/library/core/sofortLibTransactionData.inc.php');

            // initialize
            $sOrderId = $oConfig->getRequestParameter('orderid');
            $sTransactionId = $oConfig->getRequestParameter('transactionid');
            $sConfigKey = $oConfig->getConfigParam('sTroGatewayConfKey');

            // Request the current transaction-status.
            $oSofort = new SofortLibTransactionData($sConfigKey);
            $oSofort->addTransaction($sTransactionId);
            $oSofort->sendRequest();
            $sStatus = $oSofort->getStatus();

            if ($this->troSOFORTOrderHasBeenPayed($sStatus) && $this->troSOFORTOrderIsNotFinishedYet($sTransactionId, $sOrderId))
            {
                $blContinueExecuteSUOrder = true;
            }

            return $blContinueExecuteSUOrder;
        }

        /**
         * Status "pending": Data have been recorded by SOFORT AG but the SOFORT AG did not written off yet.
         *                      The order is counted as payed anyway.
         *
         * Status: "received": SOFORT AG has written off and received the money.
         *
         * @param $sPaymentStatus
         *
         * @return bool
         * @author tronet GmbH
         * @since 7.0.0
         */
        protected function troSOFORTOrderHasBeenPayed($sPaymentStatus)
        {
            $aValidPaymentStatus = array('pending', 'received', 'untraceable');

            return (in_array($sPaymentStatus, $aValidPaymentStatus));
        }

        /**
         * Determine whether current order with the present Transaction-ID is opened (status: NOT_FINISHED)
         *
         * @param $sTransactionId
         * @param $sOrderId
         *
         * @return string
         * @author tronet GmbH
         */
        protected function troSOFORTOrderIsNotFinishedYet($sTransactionId, $sOrderId)
        {
            $oDb = oxDb::getDb();

            $sSql = "select oxid from oxorder where oxpaymenttype = 'trosofortgateway_su' 
                      and oxtransstatus = 'NOT_FINISHED'
                      and oxtransid = " . $oDb->quote($sTransactionId) . "
                      and oxid = " . $oDb->quote($sOrderId);

            return $oDb->getOne($sSql);
        }
    }
