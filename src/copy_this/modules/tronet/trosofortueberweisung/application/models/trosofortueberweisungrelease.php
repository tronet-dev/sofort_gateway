<?php

    /**
     * Model class trosofortueberweisungrelease.
     *
     * @file          trosofortueberweisung_main.php
     * @link          http://www.tro.net
     * @copyright (C) tronet GmbH 2018
     * @package       modules
     * @addtogroup    core
     * @author        tronet GmbH
     * @since         7.0.0
     */
    class trosofortueberweisungrelease
    {
        /**
         * @var string $_sVersion
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_sVersion;
        /**
         * @var string $_sDownload
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_sDownload;
        /**
         * @var trosofortueberweisungreleaserequirements $_oSofortgatewayReleaseRequirements
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_oSofortgatewayReleaseRequirements;
        /**
         * @var string $_sArchiveName
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_sArchiveName;
        /**
         * @var string $_sExtractDirectoryInZip
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_sExtractDirectoryInZip;

        /**
         * trosofortueberweisungrelease constructor.
         *
         * @param string $sVersion
         * @param string $sDownload
         * @param string $sMinimumOxidVersionCe
         * @param string $sMinimumOxidVersionPe
         * @param string $sMinimumOxidVersionEe
         * @param string $sMinimumPhpVersion
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function __construct($sVersion, $sDownload, $sMinimumOxidVersionCe, $sMinimumOxidVersionPe, $sMinimumOxidVersionEe, $sMinimumPhpVersion)
        {
            $this->setVersion($sVersion);
            $this->setDownload($sDownload);

            $oRequirements = oxNew('trosofortueberweisungreleaserequirements', $sMinimumOxidVersionCe, $sMinimumOxidVersionPe, $sMinimumOxidVersionEe, $sMinimumPhpVersion);
            $this->setSofortgatewayReleaseRequirements($oRequirements);

            $sVersionUnderscored = str_replace('.', '_', $this->getVersion());
            $this->setExtractDirectoryInZip('Oxid-Sofortueberweisung-' . $sVersionUnderscored . '/copy_this');

            $this->setArchiveName('Oxid-Sofortueberweisung-' . $sVersionUnderscored . '.zip');
        }

        /**
         * Set the version.
         *
         * @param string $sVersion
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function setVersion($sVersion)
        {
            $this->_sVersion = $sVersion;
        }

        /**
         * Set the download url.
         *
         * @param string $sDownload
         *
         * @return trosofortueberweisungrelease
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function setDownload($sDownload)
        {
            $this->_sDownload = $sDownload;

            return $this;
        }

        /**
         * @param trosofortueberweisungreleaserequirements $oSOFORTReleaseRequirements
         *
         * @return trosofortueberweisungrelease
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function setSofortgatewayReleaseRequirements($oSOFORTReleaseRequirements)
        {
            $this->_oSofortgatewayReleaseRequirements = $oSOFORTReleaseRequirements;

            return $this;
        }

        /**
         * Get the release version. Optionally separator dot can be
         * adjusted as required. By default a dot is used as separator.
         *
         * @param string|null $sSeparator
         *
         * @return string
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getVersion($sSeparator = null)
        {
            if ($sSeparator != null)
            {
                $sReturn = str_replace('.', '_', $this->_sVersion);
            }
            else
            {
                $sReturn = $this->_sVersion;
            }

            return $sReturn;
        }

        /**
         * @param string $sExtractDirectoryInZip
         *
         * @return trosofortueberweisungrelease
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function setExtractDirectoryInZip($sExtractDirectoryInZip)
        {
            $this->_sExtractDirectoryInZip = $sExtractDirectoryInZip;

            return $this;
        }

        /**
         * @param mixed $sArchiveName
         *
         * @return trosofortueberweisungrelease
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function setArchiveName($sArchiveName)
        {
            $this->_sArchiveName = $sArchiveName;

            return $this;
        }

        /**
         * Checks whether current version is compatible with passed parameters and whether current release version is
         * higher.
         *
         * @param string $sModuleVersion
         * @param string $sOxidEdition
         * @param string $sOxidVersion
         * @param string $sPhpVersion
         *
         * @return bool
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function doesModuleVersionSatisfyInstalledVersion($sModuleVersion, $sOxidEdition, $sOxidVersion, $sPhpVersion)
        {
            return ($this->getSofortgatewayReleaseRequirements()->doesModuleVersionSatisfyInstalledVersion($sOxidEdition, $sOxidVersion, $sPhpVersion) && version_compare($sModuleVersion, $this->getVersion(), '<'));
        }

        /**
         * @return trosofortueberweisungreleaserequirements
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getSofortgatewayReleaseRequirements()
        {
            return $this->_oSofortgatewayReleaseRequirements;
        }

        /**
         * Get the download url.
         *
         * @return string
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getDownload()
        {
            return $this->_sDownload;
        }

        /**
         * @return string
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getArchiveName()
        {
            return $this->_sArchiveName;
        }

        /**
         * @return string
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getExtractDirectoryInZip()
        {
            return $this->_sExtractDirectoryInZip;
        }
    }