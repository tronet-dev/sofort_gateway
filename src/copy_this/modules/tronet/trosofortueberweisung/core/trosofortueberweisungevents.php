<?php

    /**
     * Event handler class for OXID eShop module tronet/trosofortueberweisung.
     *
     * Provides actions executed on module (de)activation.
     *
     * @file          trosofortueberweisungevents.php
     * @link          http://www.tro.net
     * @copyright (C) tronet GmbH 2018
     * @package       modules
     * @addtogroup    core
     * @extend        oxView
     */
    class trosofortueberweisungevents extends oxView
    {
        /**
         * Performs required actions when activating the module.
         *
         * @author tronet GmbH
         */
        public static function onActivate()
        {
            trosofortueberweisungevents::handleTroGatewayLog();
            trosofortueberweisungevents::handleOxPayments();
            trosofortueberweisungevents::handleOxorder();
            trosofortueberweisungevents::setPaymentMethodActiveStatus('trosofortgateway_su', 1);
            
            // Views aktualisieren
            $oMetaData = oxNew('oxDbMetaDataHandler');
            $oMetaData->updateViews();            

            // tmp-Ordner leeren
            trosofortueberweisungevents::troClearTmp();
        }

        /**
         * Creates trogatewaylog-table if it does not exists yet.
         *
         * @author tronet GmbH
         */
        public static function handleTroGatewayLog()
        {
            $sSqlFileContent = trosofortueberweisungevents::getSqlFileContent('trogatewaylog.sql');
            oxDb::getDb()->execute($sSqlFileContent);
        }

        /**
         * Inserts SOFORT AG payment method into oxPayment table.
         *
         * @author tronet GmbH
         */
        public static function handleOxPayments()
        {
            $sSqlFileContent = trosofortueberweisungevents::getSqlFileContent('oxpayments.sql');
            oxDb::getDb()->execute($sSqlFileContent);
        }

        /**
         * Creates new columns in oxorder.
         *
         * @author tronet GmbH
         */
        public static function handleOxorder()
        {
            $sSqlFileContent = trosofortueberweisungevents::getSqlFileContent('oxorder.sql');
            try {
                oxDb::getDb()->execute($sSqlFileContent);
            } catch (Exception $oE) {
            }
        }

        /**
         * Loads the content of defined sql file and returns the value.
         *
         * @param $sSqlFile
         *
         * @return string
         * @author tronet GmbH
         * @since 7.0.0
         */
        public static function getSqlFileContent($sSqlFile)
        {
            $sShopBasePath = getShopBasePath();

            $sDirSep = DIRECTORY_SEPARATOR;
            $sPathToModule = $sShopBasePath.'modules'.$sDirSep.'tronet'.$sDirSep.'trosofortueberweisung'.$sDirSep;
            $sPathToModuleSqlFiles = $sPathToModule.'library'.$sDirSep.'sql'.$sDirSep;

            return file_get_contents($sPathToModuleSqlFiles.$sSqlFile);
        }

        /**
         * Performs required actions when deactivating the module.
         */
        public static function onDeactivate()
        {
            trosofortueberweisungevents::setPaymentMethodActiveStatus('trosofortgateway_su', 0);
        }

        /**
         * Set the active status of a payment method.
         *
         * @param $sPaymentMethod
         * @param $iStatus
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public static function setPaymentMethodActiveStatus($sPaymentMethod, $iStatus)
        {
            $oPayment = oxNew('oxpayment');
            if ($oPayment->load($sPaymentMethod))
            {
                $oPayment->oxpayments__oxactive = new oxField($iStatus);
                $oPayment->save();
            }
        }
        
        /**
         * Clear tmp
         *
         * @author tronet GmbH
         * @since  7.0.6
         */
        public static function troClearTmp()
        {
            foreach (glob(getShopBasePath() . "tmp/*_oxorder_*") as $filename) 
            {
                @unlink($filename);
            }
        }        
    }
