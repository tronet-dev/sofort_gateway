<?php

    namespace Tronet\Trosofortueberweisung\Core;

    use OxidEsales\Eshop\Application\Model\Payment;
    use OxidEsales\Eshop\Core\DatabaseProvider;
    use OxidEsales\Eshop\Core\Exception\ConnectionException;
    use OxidEsales\Eshop\Core\Field;

    /**
     * Event handler class for OXID eShop module tronet/trosofortueberweisung.
     *
     * @link          http://www.tro.net
     * @copyright (c) tronet GmbH 2017
     * @author        tronet GmbH
     *
     * @since         7.0.0
     * @version       8.0.0
     */
    class Events
    {
        /**
         * Performs required actions when activating the module.
         *
         * @return bool
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public static function onActivate()
        {
            $aSqlFiles = [
                'trogatewaylog.sql',
                'oxpayments.sql',
            ];

            foreach ($aSqlFiles as $sSqlFile)
            {
                self::troHandleSqlFile($sSqlFile);
            }

            self::setTroPaymentMethodActiveStatus('trosofortgateway_su', 1);

            return true;
        }

        /**
         * Executes sql statement defined in $sSqlFile where $sSqlFile needs to be located within directory as defined
         * in `\Tronet\Trosofortueberweisung\Core\Events::getTroSqlFileContent`.
         *
         * @param string $sSqlFile
         *
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public static function troHandleSqlFile($sSqlFile)
        {
            try
            {
                $sSqlFileContent = self::getTroSqlFileContent($sSqlFile);
                DatabaseProvider::getDb()->execute($sSqlFileContent);
            }
            catch (ConnectionException $oConnectionException)
            {
            }
        }

        /**
         * Set the active status of a payment method.
         *
         * @param string $sPaymentMethod
         * @param int    $iStatus
         *
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public static function setTroPaymentMethodActiveStatus($sPaymentMethod, $iStatus)
        {
            $oPayment = oxNew(Payment::class);
            if ($oPayment->load($sPaymentMethod))
            {
                $oPayment->oxpayments__oxactive = new Field($iStatus);
                $oPayment->save();
            }
        }

        /**
         * Loads the content of defined sql file and returns the value.
         *
         * @param string $sSqlFile
         *
         * @return string
         * 
         * @author tronet GmbH
         * @since  7.0.0
         * @version 8.0.0
         */
        public static function getTroSqlFileContent($sSqlFile)
        {
            $sShopBasePath = getShopBasePath();

            $sPathToModule = $sShopBasePath . 'modules' . DIRECTORY_SEPARATOR . 'tronet' . DIRECTORY_SEPARATOR . 'trosofortueberweisung' . DIRECTORY_SEPARATOR;
            $sPathToModuleSqlFiles = $sPathToModule . 'library' . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR;
            $sFile = $sPathToModuleSqlFiles . $sSqlFile;

            $sFileContent = '';

            if (file_exists($sFile) && is_readable($sFile))
            {
                $sFileContent = file_get_contents($sFile);
            }

            return $sFileContent;
        }

        /**
         * Performs required actions when deactivating the module.
         *
         * @return bool
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public static function onDeactivate()
        {
            self::setTroPaymentMethodActiveStatus('trosofortgateway_su', 0);

            return true;
        }
    }
