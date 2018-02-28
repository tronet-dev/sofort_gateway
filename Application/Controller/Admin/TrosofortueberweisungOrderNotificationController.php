<?php

    namespace Tronet\Trosofortueberweisung\Application\Controller\Admin;

    use OxidEsales\Eshop\Core\DatabaseProvider;
    use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
    use OxidEsales\Eshop\Application\Model\ListObject;
    use OxidEsales\Eshop\Application\Model\Order;
    use OxidEsales\Eshop\Core\Field;
    use Tronet\Trosofortueberweisung\Application\Model\TrosofortueberweisungGatewayLog;

    /**
     * Admin order overview manager.
     * Collects order payment status information, updates it on user submit, etc.
     * Admin Menu: Orders -> Display Orders -> log.
     *
     * @link          http://www.tro.net
     * @copyright (c) tronet GmbH 2018
     * @author        tronet GmbH
     *
     * @since         7.0.0
     * @version       8.0.0
     */
    class TrosofortueberweisungOrderNotificationController extends AdminDetailsController
    {
        /**
         * @var ThisTemplate $_sThisTemplate
         * 
         * @author        tronet GmbH
         * @since         8.0.0
         * @version       8.0.0
         */
        protected $_sThisTemplate = 'trosofortueberweisungorder_notifications.tpl';       

        /**
         * Returns formatted log data.
         *
         * @return string $sFormattedLogData
         * 
         * @author tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function getTroFormattedLogData()
        {
            $sFormattedLogData = '';
            $oTrosofortueberweisungGatewayLog = $this->getTroLog();
            if ($oTrosofortueberweisungGatewayLog instanceof TrosofortueberweisungGatewayLog)
            {
                $sFormattedLogData = 'Timestamp: ' . $oTrosofortueberweisungGatewayLog->trogatewaylog__timestamp->value . "\n";
                $sFormattedLogData .= 'Status: ' . $oTrosofortueberweisungGatewayLog->trogatewaylog__status->value . "\n";
                $sFormattedLogData .= 'Status-Reason: ' . $oTrosofortueberweisungGatewayLog->trogatewaylog__statusreason->value . "\n";
                $sFormattedLogData .= 'Transaction-ID: ' . $oTrosofortueberweisungGatewayLog->trogatewaylog__transactionid->value . "\n";
            }

            return $sFormattedLogData;
        }

        /**
         * Loads trogatewaylog-entry from DB.
         *
         * @return TrosofortueberweisungGatewayLog|bool
         * 
         * @author tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function getTroLog()
        {
            $sLogOxid = $this->getTroLogOxid();
            $oReturn = false;
            if (isset($sLogOxid))
            {
                $oReturn = oxNew(TrosofortueberweisungGatewayLog::class);
                $oReturn->load($sLogOxid);
            }

            return $oReturn;
        }

        /**
         * Returns value of request param log_oxid.
         *
         * @return string
         * 
         * @author tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function getTroLogOxid()
        {
            return $this->getConfig()->getRequestParameter('log_oxid');
        }

        /**
         * Loads trogatewaylog-entries from DB
         *
         * @return ListObject $oLogs
         * 
         * @author tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function getTroAllLogs()
        {
            $oTrosofortueberweisungGatewayLog = oxNew(ListObject::class, TrosofortueberweisungGatewayLog::class);
            $sOxid = $this->getEditObjectId();
            if (isset($sOxid) && $sOxid !== '1')
            {
                $oOrder = oxNew(Order::class);
                $oOrder->load($sOxid);

                $oDb = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
                $sSqlSelect = "select * from trogatewaylog where transactionid='{$oOrder->oxorder__oxtransid->value}' order by timestamp DESC";
                $aData = $oDb->getAll($sSqlSelect);

                $oTrosofortueberweisungGatewayLog->assign($aData);
            }

            return $oTrosofortueberweisungGatewayLog;
        }

        /**
         * Extends method by our needs.
         *
         * Saves trogatewaylog-entry text changes.
         * 
         * @author tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function save()
        {
            parent::save();

            $oOrder = oxNew(Order::class);
            if ($oOrder->load($this->getEditObjectId()))
            {
                $sTroGatewayLogTransactionId = $this->getConfig()->getRequestParameter('trogatewaylog__transactionid');

                $oTrosofortueberweisungGatewayLog = oxNew(TrosofortueberweisungGatewayLog::class);
                $oTrosofortueberweisungGatewayLog->load($this->getTroLogOxid());
                $oTrosofortueberweisungGatewayLog->trogatewaylog__transactionid = new Field($sTroGatewayLogTransactionId);
                $oTrosofortueberweisungGatewayLog->save();
            }
        }

        /**
         * Extends method by our needs.
         *
         * Deletes trogatewaylog-entry from DB
         * 
         * @author tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function delete()
        {
            $oTrosofortueberweisungGatewayLog = oxNew(TrosofortueberweisungGatewayLog::class);
            $oTrosofortueberweisungGatewayLog->delete($this->getTroLogOxid());
        }
    }
