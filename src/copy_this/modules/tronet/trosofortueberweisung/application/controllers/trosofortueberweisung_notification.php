    <?php

    /**
     * Notifications-Controller
     *
     * Collects order paymentstatus information from Sofort., stores them to DB
     * and updates orders (cancel, paymentdate)
     *
     * @link          http://www.tro.net
     * @copyright (c) tronet GmbH 2018
     * @author        tronet GmbH
     *
     * @since         7.0.0
     * @version       7.0.5
     */
    class trosofortueberweisung_notification extends oxUBase
    {
        /**
         * process the transaction status
         *
         * @throws ConnectionException from SofortueberweisungNotificationController::_troUpdateOrderRoutine()
         *
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 7.0.3
         */
        public function render()
        {
            parent::render();

            // Heart of this controller
            $this->_troUpdateOrderByStatus();

            // just exit, as we are not in frontend here
            oxRegistry::getUtils()->showMessageAndExit('No info received');
        }

        /**
         * Wrapper function to update order depending on the transactions status
         *
         * @throws ConnectionException from _troCancelOrder or _troSetOrderPaid
         *
         * @author  tronet GmbH
         * @since   7.0.3
         * @version 7.0.3
         */
        protected function _troUpdateOrderByStatus()
        {
            $sTransactionId = $this->_getTroTransactionID();
            $oTransactionData = $this->_getTroTransactionData($sTransactionId);
            $this->_troStoreGatewayLog($oTransactionData);

            switch ($oTransactionData->getStatus())
            {
                // "Deutsche Handelsbank" account - waiting for another status
                case 'pending':
                    $this->_troFinalizeOrderIfStatusNotFinished($oTransactionData);
                    oxRegistry::getUtils()->showMessageAndExit('payment pending');

                // "Deutsche Handelsbank" account - money not received
                case 'loss':
                    $this->_troFinalizeOrderIfStatusNotFinished($oTransactionData);
                    $this->_troCancelOrder($oTransactionData);
                    break;

                // "Deutsche Handelsbank" account - money received
                case 'received':
                    $this->_troFinalizeOrderIfStatusNotFinished($oTransactionData);
                    $this->_troSetOrderPaid($oTransactionData);
                    break;

                // no "Deutsche Handelsbank" account
                case 'untraceable':
                    $this->_troFinalizeOrderIfStatusNotFinished($oTransactionData);
                    $this->_troSetOrderPaid($oTransactionData);
                    break;

                // money refunded
                case 'refunded':
                    $this->_troFinalizeOrderIfStatusNotFinished($oTransactionData);
                    $this->_troCancelOrder($oTransactionData);
                    break;
            }
        }

        /**
         * get the transaction-ID
         *
         * @return string $sTransactionId
         *
         * @author  tronet GmbH
         * @since   7.0.3
         * @version 7.0.3
         */
        protected function _getTroTransactionID()
        {
            $sTransactionId = $this->getConfig()->getRequestParameter('transactionid');
            if ($sTransactionId == '')
            {
                $sModuleDirectory = oxRegistry::getConfig()->getModulesDir();
                require_once($sModuleDirectory.'tronet/trosofortueberweisung/library/core/sofortLibNotification.inc.php');

                // create SofortLib_Notification-object and fetch transaction-id
                $oNotification = $this->_getTroSofortLibNotificationObject();
                $oNotification->getNotification(file_get_contents('php://input'));
                $sTransactionId = $oNotification->getTransactionId();

                if ($sTransactionId == '')
                {
                    oxRegistry::getUtils()->showMessageAndExit('No info received');
                }
            }

            return $sTransactionId;
        }

        /**
         * @return Notification
         *
         * @author  tronet GmbH
         * @since   7.0.3
         * @version 7.0.3
         */
        protected function _getTroSofortLibNotificationObject()
        {
            return new SofortLibNotification();
        }

        /**
         * get the transaction data
         *
         * @param string $sTransactionId
         *
         * @return TransactionData $oTransactionData
         *
         * @author  tronet GmbH
         * @since   7.0.3
         * @version 7.0.3
         */
        protected function _getTroTransactionData($sTransactionId)
        {
            $sModuleDirectory = oxRegistry::getConfig()->getModulesDir();
            require_once($sModuleDirectory.'tronet/trosofortueberweisung/library/core/sofortLibTransactionData.inc.php');

            // create SofortLib_TransactionData-object and fetch some information for the transaction-id retrieved above
            $oTransactionData = $this->_getTroSofortLibTransactionDataObject();
            $oTransactionData->addTransaction($sTransactionId);
            $oTransactionData->sendRequest();

            return $oTransactionData;
        }

        /**
         * @return TransactionData
         *
         * @author  tronet GmbH
         * @since   7.0.3
         * @version 7.0.3
         */
        protected function _getTroSofortLibTransactionDataObject()
        {
            $sTroGatewayConfigKey = oxRegistry::getConfig()->getConfigParam('sTroGatewayConfKey');
            return new SofortLibTransactionData($sTroGatewayConfigKey);
        }

        /**
         * stores received information to log-table trogatewaylog
         *
         * @param TransactionData $oTransactionData
         *
         * @author  tronet GmbH
         * @since   7.0.3
         * @version 7.0.3
         */
        protected function _troStoreGatewayLog($oTransactionData)
        {
            $sTransactionId = $oTransactionData->getTransaction();
            $oTroGatewayLog = oxNew('trosofortueberweisunggatewaylog');
            $aTroGatewayLogNewestEntry = $oTroGatewayLog->getTroNewestLog($sTransactionId);

            // Datum des bereits in der Datenbank gespeicherten Eintrags ist ungleich der aktuell vorliegenden Statusaenderung
            if ($aTroGatewayLogNewestEntry['STATUS'] != $oTransactionData->getStatus())
            {
                $oTroGatewayLog->trogatewaylog__transactionid = new oxField($sTransactionId);
                $oTroGatewayLog->trogatewaylog__status = new oxField($oTransactionData->getStatus());
                $oTroGatewayLog->trogatewaylog__statusreason = new oxField($oTransactionData->getStatusReason());
                $oTroGatewayLog->trogatewaylog__timestamp = new oxField($oTransactionData->getStatusModifiedTime());
                $oTroGatewayLog->save();
            }
        }

        /**
         * Wird nach Bezahlung auf der Seite von Sofortueberweisung
         * z.B. der Browser geschlossen
         * und der User nicht mehr in den Shop zurückgeleitet,
         * führe die Bestellung nun zu Ende
         *
         * @param TransactionData $oTransactionData
         *
         * @author  tronet GmbH
         * @since   7.0.3
         * @version 7.0.5
         */
        protected function _troFinalizeOrderIfStatusNotFinished($oTransactionData)
        {
            $sTransactionId = $oTransactionData->getTransaction();
            $sOrderId = $this->_getTroOrderID($oTransactionData);
            $oOrder = oxNew('oxorder');
            $oOrder->load($sOrderId);

            if ($oOrder->oxorder__oxpaymenttype->value == 'trosofortgateway_su'
             && $oOrder->oxorder__oxtransstatus->value == 'NOT_FINISHED'
             && $oOrder->oxorder__oxtransid->value == $sTransactionId)
            {
                $this->setAdminMode(true);

                $oUser = $oOrder->getOrderUser();
                $oBasket = $oOrder->getTroOrderBasket();

                // Finish oxOrder::finalizeOrder
                $iSuccess = $oOrder->troContinueFinalizeOrder($oBasket, $oUser);

                // Finish order::execute
                // performing special actions after user finishes order (assignment to special user groups)
                $oUser->onOrderExecute($oBasket, $iSuccess);
            }
        }

        /**
         * cancels order
         *
         * @param TransactionData $oTransactionData
         *
         * @throws ConnectionException
         *
         * @author  tronet GmbH
         * @since   7.0.3
         * @version 7.0.3
         */
        protected function _troCancelOrder($oTransactionData)
        {
            $sOrderId = $this->_getTroOrderID($oTransactionData);

            if ($sOrderId)
            {
                $oOrder = oxNew('oxorder');
                $oOrder->load($sOrderId);
                $oOrder->cancelOrder();

                $sSqlUpdate = "UPDATE oxorder SET oxpaid='' where oxid='$sOrderId'";
                $oDB = oxDb::getDb();
                $oDB->execute($sSqlUpdate);

                oxRegistry::getUtils()->showMessageAndExit('Order canceled');
            }
            oxRegistry::getUtils()->showMessageAndExit('Order not found');
        }

        /**
         * sets order as paid
         *
         * @param TransactionData $oTransactionData
         *
         * @throws ConnectionException
         *
         * @author  tronet GmbH
         * @since   7.0.3
         * @version 7.0.3
         */
        protected function _troSetOrderPaid($oTransactionData)
        {
            $sOrderId = $this->_getTroOrderID($oTransactionData);

            if ($sOrderId)
            {
                $sDate = $oTransactionData->getStatusModifiedTime();

                $sSqlUpdate = "UPDATE oxorder SET oxpaid='$sDate' where oxid='$sOrderId'";
                $oDB = oxDb::getDb();
                $oDB->execute($sSqlUpdate);
                oxRegistry::getUtils()->showMessageAndExit('Order updated');
            }
            oxRegistry::getUtils()->showMessageAndExit('Order not found');
        }

        /**
         * gets oxorder.oxid for the current transaction-id
         *
         * @param TransactionData $oTransactionData
         *
         * @return string
         * @throws ConnectionException
         *
         * @author  tronet GmbH
         * @since   7.0.3
         * @version 7.0.3
         */
        protected function _getTroOrderID($oTransactionData)
        {
            $sTransactionId = $oTransactionData->getTransaction();
            $sSqlSelect = "SELECT OXID FROM oxorder where oxtransid='$sTransactionId'";

            $oDB = oxDb::getDb();
            $sOrderId = $oDB->getOne($sSqlSelect);
            return $sOrderId;
        }
    }
