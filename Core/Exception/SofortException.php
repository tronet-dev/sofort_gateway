<?php

    namespace Tronet\Trosofortueberweisung\Core\Exception;

    use OxidEsales\Eshop\Core\Config;
    use OxidEsales\Eshop\Core\Exception\StandardException;
    use OxidEsales\Eshop\Core\Registry;

    /**
     * Extends oxException system.
     *
     * @link          http://www.tro.net
     * @copyright (c) tronet GmbH 2018
     * @author        tronet GmbH
     *
     * @since         7.0.0
     * @version       8.0.0
     */
    class SofortException extends StandardException
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
         * @var Config|null
         * @author tronet GmbH
         */
        protected $_oConfig = null;

        /**
         * Default constructor
         *
         * @param string      $sMessage exception message
         * @param integer     $iCode    exception code
         * @param string      $sTrace
         * @param Config|null $oConfig
         *
         * @author tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function __construct($sMessage = 'not set', $iCode = 0, $sTrace = '', $oConfig = null)
        {
            $this->_sTrace = $sTrace;
            $this->_oConfig = $oConfig;
            parent::__construct($sMessage, $iCode);
        }

        /**
         * Builds a string representing current SofortException.
         * Overrides oxException::getString()
         *
         * @return string
         * 
         * @author tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function getString()
        {
            $aModuleVersions = Registry::getConfig()->getConfigParam('aModuleVersions');

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
            $sToString .= "\n" . 'Message from Sofort.-server: ' . $this->message . "\n";

            return $sToString;
        }
    }
