<?php

    namespace Tronet\Trosofortueberweisung\Core\Utility;

    use OxidEsales\Eshop\Core\Registry;
    use Tronet\Trosofortueberweisung\Core\SofortConfiguration;

    /**
     * Provides methods for logging actions.
     *
     * @link          http://www.tro.net
     * @copyright (c) tronet GmbH 2018
     * @author        tronet GmbH
     *
     * @since         7.0.0
     * @version       8.0.0
     */
    class LoggingUtility
    {
        /**
         * @var SofortConfiguration|null $_oSofortConfiguration
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        protected $_oSofortConfiguration = null;

        /**
         * @param string $sLogLevel INFO|WARNING|...
         * @param string $sMessage
         *
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function troWriteToLog($sLogLevel, $sMessage)
        {
            $sLogTime = date('Y-m-d_h-i-s', time());
            $sFinalLogMessage = sprintf($this->getTroLogTemplate(), $sLogTime, $sLogLevel, $sMessage);
            Registry::getUtils()->troWriteToLog($sFinalLogMessage, $this->getTroSOFORTConfig()->getTroLogFile());
        }

        /**
         * Returns the log template. Where the first placeholder
         * is the date, the second the level and the third the
         * actual message to log.
         *
         * @return string
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function getTroLogTemplate()
        {
            return '[%1$s][%2$s] %3$s' . "\n";
        }

        /**
         * @return SofortConfiguration
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function getTroSOFORTConfig()
        {
            if ($this->_oSofortConfiguration === null)
            {
                $this->_oSofortConfiguration = oxNew(SofortConfiguration::class);
            }

            return $this->_oSofortConfiguration;
        }

        /**
         * @param SofortConfiguration $oSofortConfiguration
         *
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function setTroSOFORTConfig($oSofortConfiguration)
        {
            $this->_oSofortConfiguration = $oSofortConfiguration;
        }
    }
