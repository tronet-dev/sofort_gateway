<?php

    namespace Tronet\Trosofortueberweisung\Application\Controller;

    use OxidEsales\Eshop\Core\Database\Adapter\DatabaseInterface;
    use OxidEsales\Eshop\Core\DatabaseProvider;
    use OxidEsales\Eshop\Core\Exception\ArticleInputException;
    use OxidEsales\Eshop\Core\Exception\NoArticleException;
    use OxidEsales\Eshop\Core\Exception\OutOfStockException;
    use OxidEsales\Eshop\Core\Registry;
    use OxidEsales\Eshop\Core\Session;
    use OxidEsales\Eshop\Core\UtilsView;
    use Sofort\SofortLib\TransactionData;

    /**
     * Order manager. Arranges user ordering data, checks/validates
     * it, on success stores ordering data to DB.
     *
     * NEW: sets ordernr before finalizing and continues finalizing, if stopped during paymentexecution
     *
     * @link          http://www.tro.net
     * @copyright (c) tronet GmbH 2017
     * @author        tronet GmbH
     *
     * @since         7.0.0
     * @version       8.0.0
     */
    class TrosofortueberweisungOrderController extends TrosofortueberweisungOrderController_parent
    {
        /**
         * Finalizes order, if we stopped during executePayment
         *
         * @return string
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function troContinueExecute()
        {
            $oSession = Registry::getSession();

            // In order to avoid abuse we first verify that a previously initiated order has been payed.
            // In case the test fails for any reason order step 3 will be displayed.
            if (!$this->_troContinueExecuteSofortueberweisungOrder())
            {
                return 'payment';
            }

            /*
             * As this function is triggered due to external processes variables $oUser, $oBasket, $oOrder have
             * to be initialized.
             */

            // additional check if we really really have a user now
            $oUser = $this->getUser();
            if (!$oUser) {
                return 'user';
            }

            $oOrder = $oSession->getVariable('trosuoxorder');
            $oBasket = $oOrder->getTroOrderBasket();
            
            if ($oBasket->getProductsCount())
            {
                try
                {
                    // Finish oxOrder::finalizeOrder
                    $iSuccess = $oOrder->troContinueFinalizeOrder($oBasket, $oUser);

                    ////////////////////////////////////////////////////////////
                    //// Following is the rest of original oxOrder::execute

                    // performing special actions after user finishes order (assignment to special user groups)
                    $oUser->onOrderExecute($oBasket, $iSuccess);

                    // proceeding to next view
                    return $this->_getNextStep($iSuccess);
                } catch (\OxidEsales\Eshop\Core\Exception\OutOfStockException $oEx) {
                    $oEx->setDestination('basket');
                    \OxidEsales\Eshop\Core\Registry::getUtilsView()->addErrorToDisplay($oEx, false, true, 'basket');
                } catch (\OxidEsales\Eshop\Core\Exception\NoArticleException $oEx) {
                    \OxidEsales\Eshop\Core\Registry::getUtilsView()->addErrorToDisplay($oEx);
                } catch (\OxidEsales\Eshop\Core\Exception\ArticleInputException $oEx) {
                    \OxidEsales\Eshop\Core\Registry::getUtilsView()->addErrorToDisplay($oEx);
                }
            }
        }

        /**
         * _troContinueExecuteSofortueberweisungOrder
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
         * We assume order should not be continued. If everything is ok the return var $blContinueExecuteSofortueberweisungOrder is
         * set to true.
         *
         * @return boolean $blContinueExecuteSofortueberweisungOrder
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        protected function _troContinueExecuteSofortueberweisungOrder()
        {
            $blContinueExecuteSofortueberweisungOrder = false;
            $oConfig = $this->getConfig();

            // initialize
            $sOrderId = $oConfig->getRequestParameter('orderid');
            $sTransactionId = $oConfig->getRequestParameter('transactionid');
            $sConfigKey = $oConfig->getConfigParam('sTroGatewayConfKey');

            // Request the current transaction-status.
            $oTransactionData = new TransactionData($sConfigKey);
            $oTransactionData->addTransaction($sTransactionId);
            $oTransactionData->sendRequest();
            $sTransactionDataStatus = $oTransactionData->getStatus();


            if ($this->_troSOFORTOrderHasBeenPayed($sTransactionDataStatus) && $this->_troSOFORTOrderIsNotFinishedYet($sTransactionId, $sOrderId))
            {
                $blContinueExecuteSofortueberweisungOrder = true;
            }

            return $blContinueExecuteSofortueberweisungOrder;
        }

        /**
         * Status "pending": Data have been recorded by SOFORT AG but the SOFORT AG did not written off yet.
         *                      The order is counted as payed anyway.
         *
         * Status: "received": SOFORT AG has written off and received the money.
         *
         * @param string $sPaymentStatus
         *
         * @return bool $blOrderHasBeenPayed
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        protected function _troSOFORTOrderHasBeenPayed($sPaymentStatus)
        {
            $blOrderHasBeenPayed = false;
            $aValidPaymentStatus = [
                'pending',
                'received',
                'untraceable',
            ];

            if (in_array($sPaymentStatus, $aValidPaymentStatus, true))
            {
                $blOrderHasBeenPayed = true;
            }

            return $blOrderHasBeenPayed;
        }

        /**
         * Determine whether current order with the present Transaction-ID is opened (status: NOT_FINISHED)
         *
         * @param string $sTransactionId
         * @param string $sOrderId
         *
         * @return bool
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        protected function _troSOFORTOrderIsNotFinishedYet($sTransactionId, $sOrderId)
        {
            $oDatabaseProvider = DatabaseProvider::getDb();

            $sSqlSelect = "select oxid from oxorder where oxpaymenttype = 'trosofortgateway_su' 
                      and oxtransstatus = 'NOT_FINISHED'
                      and oxtransid = " . $oDatabaseProvider->quote($sTransactionId) . "
                      and oxid = " . $oDatabaseProvider->quote($sOrderId);

            return $oDatabaseProvider->getOne($sSqlSelect);
        }
    }
