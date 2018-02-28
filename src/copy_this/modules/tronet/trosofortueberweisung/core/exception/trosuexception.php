<?php

    /**
     * Extends oxException system.
     *
     * @file          trosuexception.php
     * @link          http://www.tro.net
     * @copyright (C) tronet GmbH 2018
     * @package       modules
     * @addtogroup    trosofortueberweisung\core\exception
     * @extend        oxpaymentgateway
     */
    class trosuexception extends oxException
    {
        /**
         * @var string $_sFileName
         * @author tronet GmbH
         */
        protected $_sFileName = 'SOFORTGATEWAY_LOG.txt';

        /**
         * @var string $_sTrace
         * @author tronet GmbH
         */
        protected $_sTrace = '';

        /**
         * @var oxConfig|null
         * @author tronet GmbH
         */
        protected $_oConfig = null;

        /**
         * Default constructor
         *
         * @param string        $sMessage exception message
         * @param integer       $iCode    exception code
         * @param string        $sTrace
         * @param oxConfig|null $oConfig
         *
         * @author tronet GmbH
         */
        public function __construct($sMessage = "not set", $iCode = 0, $sTrace = '', $oConfig = null)
        {
            $this->_sTrace = $sTrace;
            $this->_oConfig = $oConfig;
            parent::__construct($sMessage, $iCode);
        }

        /**
         * Builds a string representing current trosuexception.
         * Overrides oxException::getString()
         *
         * @return string
         * @author tronet GmbH
         */
        public function getString()
        {
            $aModuleVersions = oxRegistry::getConfig()->getConfigParam('aModuleVersions');

            $sToString = "\n" . 'Module-Version: ' . $aModuleVersions['trosofortueberweisung'];

            if ($this->_oConfig)
            {
                $sToString .= "\n" . 'Oxid-Version: ' . $this->_oConfig->getVersion() . ' ' . $this->_oConfig->getEdition();
                $sToString .= "\n" . 'Shop-Id: ' . $this->_oConfig->getShopId();
            }

            $sToString .= "\n" . 'Time: ' . date('Y-m-d H:i:s');

            if ($this->_sTrace)
            {
                $sToString .= "\n" . 'Trace: ' . $this->_sTrace;
            }

            $sToString .= "\n" . 'Code: [' . $this->code . ']';
            $sToString .= "\n" . 'Message from SOFORT AG server: ' . $this->message . "\n";

            return $sToString;
        }
    }
