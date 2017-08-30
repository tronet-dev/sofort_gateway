<?php

    /**
     * Admin order overview manager.
     * Collects order paymentstatus information, updates it on user submit, etc.
     * Admin Menu: Orders -> Display Orders -> log.
     *
     * @file          trosofortueberweisungorder_notifications.php
     * @link          http://www.tro.net
     * @copyright (C) tronet GmbH 2017
     * @package       modules
     * @addtogroup    application/controllers/admin
     * @extend        oxAdminDetails
     * @author        tronet GmbH
     * @extend        oxAdminDetails
     */
    class trosofortueberweisungorder_notifications extends oxAdminDetails
    {
        /**
         * Executes parent method parent::render(), creates oxOrder and
         * oxList objects, passes it's data to Smarty engine and returns
         * name of template file "troorder_notifications.tpl".
         *
         * @return string
         * @author tronet GmbH
         */
        public function render()
        {
            parent::render();

            return 'trosofortueberweisungorder_notifications.tpl';
        }

        /**
         * Loads trogatewaylog-entry from DB
         *
         * @return trosofortueberweisunggatewaylog|bool
         * @author tronet GmbH
         */
        public function getLog()
        {
            $sLogOxid = $this->getLogOxid();
            $mReturn = false;
            if (isset($sLogOxid))
            {
                $mReturn = oxNew('trosofortueberweisunggatewaylog');
                $mReturn->load($sLogOxid);
            }

            return $mReturn;
        }

        public function getFLogData()
        {
            $oLog = $this->getLog();
            if ($oLog)
            {
                $s = 'Timestamp: '.$oLog->trogatewaylog__timestamp->value."\n";
                $s .= 'Status: '.$oLog->trogatewaylog__status->value."\n";
                $s .= 'Status-Reason: '.$oLog->trogatewaylog__statusreason->value."\n";
                $s .= 'Transaction-ID: '.$oLog->trogatewaylog__transactionid->value."\n";
                return $s;
            }
        }
        
        /**
         * Loads trogatewaylog-entry from DB
         *
         * @return object
         * @author tronet GmbH
         */
        public function getLogOxid()
        {
            return $this->getConfig()->getRequestParameter('log_oxid');
        }

        /**
         * Loads trogatewaylog-entries from DB
         *
         * @return oxList $oLogs
         * @author tronet GmbH
         */
        public function getAllLogs()
        {
            $oLogs = oxNew('oxlist');
            $oLogs->init('trosofortueberweisunggatewaylog');
            $sOxid = $this->getEditObjectId();
            if (isset($sOxid) && '1' !== $sOxid)
            {
                $oOrder = oxNew('oxorder');
                $oOrder->load($sOxid);
                $sSelect = "select * from trogatewaylog where transactionid='" . $oOrder->oxorder__oxtransid->value . "' order by timestamp DESC";
                $oLogs->selectString($sSelect);
            }

            return $oLogs;
        }

        /**
         * Extends method by our needs.
         *
         * Saves trogatewaylog-entry text changes.
         * @author tronet GmbH
         */
        public function save()
        {
            parent::save();

            $oOrder = oxNew('oxOrder');
            if ($oOrder->load($this->getEditObjectId()))
            {
                $sTroGatewayLogTransactionId = $this->getConfig()->getRequestParameter('trogatewaylog__transactionid');

                $oTroGatewayLog = oxNew('trosofortueberweisunggatewaylog');
                $oTroGatewayLog->load($this->getLogOxid());
                $oTroGatewayLog->trogatewaylog__transactionid = new oxField($sTroGatewayLogTransactionId);
                $oTroGatewayLog->save();
            }
        }

        /**
         * Extends method by our needs.
         *
         * Deletes trogatewaylog-entry from DB
         * @author tronet GmbH
         */
        public function delete()
        {
            $oTroGatewayLog = oxNew('trosofortueberweisunggatewaylog');
            $oTroGatewayLog->delete($this->getLogOxid());
        }
    }
