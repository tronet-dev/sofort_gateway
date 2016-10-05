<?php

    /**
     * Configuration class for module tronet/trosofortueberweisung.
     *
     * Contains e.g. links to change log or release list in xml format.
     *
     * @file          trosofortueberweisung_main.php
     * @link          http://www.tro.net
     * @copyright (C) tronet GmbH 2016
     * @package       modules
     * @addtogroup    core
     * @author        tronet GmbH
     * @since         7.0.0
     */
    class trosofortueberweisungconfig
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
         * trosofortueberweisungconfig constructor.
         * @author tronet GmbH
         * @since  7.0.0
         * @todo   Set real XML file for release list
         * @todo   Update change log link
         */
        public function __construct()
        {
            $this->_setChangeLogUrl('http://sofort.tro.net/trosofortueberweisung_changelog.txt');
            $this->_setReleaseListUrl('http://sofort.tro.net/Versions/trosofortueberweisung_versions.xml');
            $this->_setTroMetaHashLinkRaw('http://sofort.tro.net/Module-File-Hashes/trometahashes_%1$s.xml');
            $this->_setLogFile('SOFORTGATEWAY_LOG.txt');
        }

        /**
         * Set the change log url.
         *
         * @param string $sChangeLogUrl
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected function _setChangeLogUrl($sChangeLogUrl)
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
         */
        protected function _setReleaseListUrl($sReleaseListUrl)
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
         */
        protected function _setLogFile($sLogFile)
        {
            $this->_sLogFile = $sLogFile;
        }

        /**
         * Get the change log url.
         *
         * @return string $this->_sChangeLogUrl
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getChangeLogUrl()
        {
            return $this->_sChangeLogUrl;
        }

        /**
         * Get the release list url.
         *
         * @return string $this->_sReleaseListUrl
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getReleaseListUrl()
        {
            return $this->_sReleaseListUrl;
        }

        /**
         * Get the raw trometa-hash-link .
         *
         * @return string $this->_sTroMetaHashLinkRaw
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getTroMetaHashLinkRaw()
        {
            return $this->_sTroMetaHashLinkRaw;
        }

        /**
         * Get the log file.
         *
         * @return string $this->_sLogFile
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getLogFile()
        {
            return $this->_sLogFile;
        }
    }