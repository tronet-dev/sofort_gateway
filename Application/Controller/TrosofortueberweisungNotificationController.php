<?php

    namespace Tronet\Trosofortueberweisung\Application\Controller;

    use OxidEsales\Eshop\Application\Controller\FrontendController;
    use OxidEsales\Eshop\Core\DatabaseProvider;
    use OxidEsales\Eshop\Core\Exception\ConnectionException;
    use OxidEsales\Eshop\Core\Field;
    use OxidEsales\Eshop\Core\Registry;
    use OxidEsales\Eshop\Application\Model\Order;
    use Sofort\SofortLib\Notification;
    use Sofort\SofortLib\TransactionData;
    use Tronet\Trosofortueberweisung\Application\Model\TrosofortueberweisungGatewayLog;

    /**
     * Notifications-Controller
     *
     * Collects order paymentstatus information from Sofort., stores them to DB
     * and updates orders (cancel, paymentdate)
     *
     * @link          http://www.tro.net
     * @copyright (c) tronet GmbH 2017
     * @author        tronet GmbH
     *
     * @since         7.0.0
     * @version       8.0.0
     */
    class TrosofortueberweisungNotificationController extends FrontendController
    {
        /**
         * process the transaction status
         *
         * @throws ConnectionException from SofortueberweisungNotificationController::_troUpdateOrderRoutine()
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function render()
        {
            parent::render();

            // Heart of this controller
            $this->_troUpdateOrderRoutine();

            // just exit, as we are not in frontend here
            Registry::getUtils()->showMessageAndExit('No info received');
        }

        /**
         * Checks whether orders have to be updated.
         *
         * If a specific request parameter is defined with value 1 the orders
         * that have to be updated will be determined by an sql query. Otherwise
         * the default way (as implemented in
         * SofortueberweisungNotificationController::getTroTransactionData) is used.
         *
         * @throws ConnectionException from oxDb::getDb
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        protected function _troUpdateOrderRoutine()
        {
            $oConfig = $this->getConfig();
            $sTroSofortueberweisungUpdateOrder = $oConfig->getRequestParameter('trosofortueberweisung_updateorder');

            if (is_string($sTroSofortueberweisungUpdateOrder) && $sTroSofortueberweisungUpdateOrder === '1')
            {
                $oDatabaseProvider = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
                $sSqlSelect = 'SELECT OXTRANSID FROM oxorder WHERE OXPAYMENTTYPE = "trosofortgateway_su" AND OXTRANSID != "" AND OXSTORNO = 0 AND OXPAID = 0';
                $aDatabaseProviderResult = $oDatabaseProvider->getAll($sSqlSelect);
                if (is_array($aDatabaseProviderResult))
                {
                    foreach ($aDatabaseProviderResult as $aDatabaseProviderResultRow)
                    {
                        $oTransactionData = $this->getTroTransactionData($aDatabaseProviderResultRow['OXTRANSID']);
                        $this->_troUpdateOrderByTransaction($oTransactionData, false);
                    }
                    Registry::getUtils()->showMessageAndExit('mass order update finished');
                }
            }
            else
            {
                $oTransactionData = $this->getTroTransactionData();
                $this->_troUpdateOrderByTransaction($oTransactionData);
            }
        }

        /**
         * get the transaction data
         *
         * @param string|null $sTransactionId If defined this transaction is requested
         *
         * @return TransactionData $oTransactionData
         *
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function getTroTransactionData($sTransactionId = null)
        {
            // use stream variant
            if (is_string($sTransactionId) === null || strlen($sTransactionId) <= 5)
            {
                // create SofortLib_Notification-object and fetch transaction-id
                $oNotification = $this->getTroSofortLibNotificationObject();
                $oNotification->getNotification(file_get_contents('php://input'));
                $sTransactionId = $oNotification->getTransactionId();

                if ($sTransactionId === '')
                {
                    Registry::getUtils()->showMessageAndExit('No info received');
                }
            }

            // create SofortLib_TransactionData-object and fetch some information for the transaction-id retrieved above
            $oTransactionData = $this->getTroSofortLibTransactionDataObject();
            $oTransactionData->addTransaction($sTransactionId);
            $oTransactionData->sendRequest();

            return $oTransactionData;
        }

        /**
         * Wrapper function to update an order by a transaction if it's defined.
         *
         * * adds a log entry
         * * updates the actual order
         *
         * @param TransactionData $oTransactionData
         * @param bool            $blExit
         *
         * @throws ConnectionException from SofortueberweisungNotificationController::_troUpdateOrderByStatus
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        protected function _troUpdateOrderByTransaction($oTransactionData, $blExit = true)
        {
            if ($oTransactionData instanceof TransactionData && is_string($oTransactionData->getStatus()))
            {
                $this->_troStoreGatewayLog($oTransactionData);
                $this->_troUpdateOrderByStatus($oTransactionData, $blExit);
            }
        }

        /**
         * @return TransactionData
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function getTroSofortLibTransactionDataObject()
        {
            $sTroGatewayConfigKey = Registry::getConfig()->getConfigParam('sTroGatewayConfKey');

            return new TransactionData($sTroGatewayConfigKey);
        }

        /**
         * @return Notification
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function getTroSofortLibNotificationObject()
        {
            return new Notification();
        }

        /**
         * stores received information to log-table trogatewaylog
         *
         * @param TransactionData $oTransactionData
         *
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        protected function _troStoreGatewayLog($oTransactionData)
        {
            $oTrosofortueberweisungGatewayLog = oxNew(TrosofortueberweisungGatewayLog::class);
            $oTrosofortueberweisungGatewayLog->trogatewaylog__transactionid = new Field($oTransactionData->getTransaction());
            $oTrosofortueberweisungGatewayLog->trogatewaylog__status = new Field($oTransactionData->getStatus());
            $oTrosofortueberweisungGatewayLog->trogatewaylog__statusreason = new Field($oTransactionData->getStatusReason());
            $oTrosofortueberweisungGatewayLog->trogatewaylog__timestamp = new Field($oTransactionData->getStatusModifiedTime());
            $oTrosofortueberweisungGatewayLog->save();
        }

        /**
         * Wrapper function to update order depending on the transactions status
         *
         * @param TransactionData $oTransactionData
         * @param bool            $blExit By default script is terminated after cancel or update process
         *
         * @throws ConnectionException from \SofortueberweisungNotificationController::_troCancelOrder or
         *                             \SofortueberweisungNotificationController::_troSetOrderPaid
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        protected function _troUpdateOrderByStatus($oTransactionData, $blExit = true)
        {
            switch ($oTransactionData->getStatus())
            {
                // "Deutsche Handelsbank" account - waiting for another status
                case 'pending':
                    break;

                // "Deutsche Handelsbank" account - money not received
                case 'loss':
                    $this->_troCancelOrder($oTransactionData, $blExit);
                    break;

                // "Deutsche Handelsbank" account - money received
                case 'received':
                    $this->_troSetOrderPaid($oTransactionData, $blExit);
                    break;

                // no "Deutsche Handelsbank" account
                case 'untraceable':
                    $this->_troSetOrderPaid($oTransactionData, $blExit);
                    break;

                // money refunded
                case 'refunded':
                    $this->_troCancelOrder($oTransactionData, $blExit);
                    break;
            }
        }

        /**
         * cancels order
         *
         * @param TransactionData $oTransactionData
         * @param bool            $blExit By default script is terminated after cancel or update process
         *
         * @throws ConnectionException
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        protected function _troCancelOrder($oTransactionData, $blExit)
        {
            $sOrderId = $this->getTroOrderID($oTransactionData);
            $sExitMessage = 'Order not found';

            if ($sOrderId)
            {
                $oOrder = oxNew(Order::class);
                $oOrder->load($sOrderId);
                $oOrder->cancelOrder();

                $sSqlUpdate = "UPDATE oxorder SET oxpaid='' where oxid='$sOrderId'";
                DatabaseProvider::getDb()->execute($sSqlUpdate);

                $sExitMessage = 'Order canceled';
            }

            if ($blExit)
            {
                Registry::getUtils()->showMessageAndExit($sExitMessage);
            }
        }

        /**
         * sets order as paid
         *
         * @param TransactionData $oTransactionData
         * @param bool            $blExit By default script is terminated after cancel or update process
         *
         * @throws ConnectionException
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        protected function _troSetOrderPaid($oTransactionData, $blExit)
        {
            $sOrderId = $this->getTroOrderID($oTransactionData);
            $sExitMessage = 'Order not found';

            if ($sOrderId)
            {
                $sDate = $oTransactionData->getStatusModifiedTime();

                $sSqlUpdate = "UPDATE oxorder SET oxpaid='$sDate' where oxid='$sOrderId'";
                DatabaseProvider::getDb()->execute($sSqlUpdate);
                $sExitMessage = 'Order updated';
            }

            if ($blExit)
            {
                Registry::getUtils()->showMessageAndExit($sExitMessage);
            }
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
         * @since   7.0.0
         * @version 8.0.0
         */
        public function getTroOrderID($oTransactionData)
        {
            $sTransactionId = $oTransactionData->getTransaction();
            $sSqlSelect = "SELECT OXID FROM oxorder where oxtransid='$sTransactionId' ";

            return DatabaseProvider::getDb()->getOne($sSqlSelect);
        }
    }
