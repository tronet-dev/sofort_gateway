<?php

    namespace Tronet\Trosofortueberweisung\Core;

    /**
     * Configuration class for module tronet/trosofortueberweisung.
     *
     * Contains e.g. links to change log or release list in xml format.
     *
     * @link          http://www.tro.net
     * @copyright (c) tronet GmbH 2018
     * @author        tronet GmbH
     *
     * @since         7.0.0
     * @version       8.0.0
     */
    class SofortConfiguration
    {
        /**
         * @var string $_sChangeLogUrl
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_sChangeLogUrl;

        /**
         * @var string $_sReleaseListUrl
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_sReleaseListUrl;

        /**
         * @var string $_sTroMetaHashLinkRaw
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_sTroMetaHashLinkRaw;

        /**
         * @var string $_sLogFile
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_sLogFile;

        /**
         * @const string API_VERSION
         * @author tronet GmbH
         * @since  8.0.1
         */
        const API_VERSION = '2.0';
        
        /**
         * SofortConfiguration constructor.
         * 
         * @author tronet GmbH
         * @since  7.0.0
         * @version 8.0.0
         */
        public function __construct()
        {
            $this->_setTroChangeLogUrl('http://sofort.tro.net/trosofortueberweisung_changelog.txt');
            $this->_setTroReleaseListUrl('http://sofort.tro.net/Versions/trosofortueberweisung_versions.xml');
            $this->_setTroMetaHashLinkRaw('http://sofort.tro.net/Module-File-Hashes/trometahashes_%1$s.xml');
            $this->_setTroLogFile('SOFORTGATEWAY_LOG.txt');
        }

        /**
         * Set the change log url.
         *
         * @param string $sChangeLogUrl
         *
         * @author tronet GmbH
         * @since  7.0.0
         * @version 8.0.0
         */
        protected function _setTroChangeLogUrl($sChangeLogUrl)
        {
            $this->_sChangeLogUrl = $sChangeLogUrl;
        }

        /**
         * Set the release list url.
         *
         * @param string $sReleaseListUrl
         *
         * @author tronet GmbH
         * @since  7.0.0
         * @version 8.0.0
         */
        protected function _setTroReleaseListUrl($sReleaseListUrl)
        {
            $this->_sReleaseListUrl = $sReleaseListUrl;
        }

        /**
         * Set the raw trometa-hash-link.
         *
         * @param string $sTroMetaHashLinkRaw
         *
         * @author tronet GmbH
         * @since  7.0.0
         * @version 8.0.0
         */
        protected function _setTroMetaHashLinkRaw($sTroMetaHashLinkRaw)
        {
            $this->_sTroMetaHashLinkRaw = $sTroMetaHashLinkRaw;
        }

        /**
         * Set the log file
         *
         * @param string $sLogFile
         *
         * @author tronet GmbH
         * @since  7.0.0
         * @version 8.0.0
         */
        protected function _setTroLogFile($sLogFile)
        {
            $this->_sLogFile = $sLogFile;
        }

        /**
         * Get the change log url.
         *
         * @return string $this->_sChangeLogUrl
         * 
         * @author tronet GmbH
         * @since  7.0.0
         * @version 8.0.0
         */
        public function getTroChangeLogUrl()
        {
            return $this->_sChangeLogUrl;
        }

        /**
         * Get the release list url.
         *
         * @return string $this->_sReleaseListUrl
         * 
         * @author tronet GmbH
         * @since  7.0.0
         * @version 8.0.0
         */
        public function getTroReleaseListUrl()
        {
            return $this->_sReleaseListUrl;
        }

        /**
         * Get the raw trometa-hash-link .
         *
         * @return string $this->_sTroMetaHashLinkRaw
         * 
         * @author tronet GmbH
         * @since  7.0.0
         * @version 8.0.0
         */
        public function getTroMetaHashLinkRaw()
        {
            return $this->_sTroMetaHashLinkRaw;
        }

        /**
         * Get the log file.
         *
         * @return string $this->_sLogFile
         * 
         * @author tronet GmbH
         * @since  7.0.0
         * @version 8.0.0
         */
        public function getTroLogFile()
        {
            return $this->_sLogFile;
        }

        /**
         * Get the API-Version.
         *
         * @return string $this->_sApiVersion
         * 
         * @author tronet GmbH
         * @since  8.0.1
         * @version 8.0.1
         */
        public static function getTroApiVersion()
        {
            return self::API_VERSION;
        }
    }
