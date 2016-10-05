<?php

    /**
     * Model class trosofortueberweisungreleaserequirements.
     *
     * @file          trosofortueberweisungreleaserequirements.php
     * @link          http://www.tro.net
     * @copyright (C) tronet GmbH 2016
     * @package       modules
     * @addtogroup    core
     * @author        tronet GmbH
     * @since         7.0.0
     */
    class trosofortueberweisungreleaserequirements
    {
        /**
         * @var string $_sMinimumOxidVersionCe
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_sMinimumOxidVersionCe;
        /**
         * @var string $_sMinimumOxidVersionPe
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_sMinimumOxidVersionPe;
        /**
         * @var string $_sMinimumOxidVersionEe
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_sMinimumOxidVersionEe;
        /**
         * @var string $_sMinimumPhpVersion
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_sMinimumPhpVersion;

        /**
         * trosofortueberweisungreleaserequirements constructor.
         *
         * @param string $sMinimumOxidVersionCe
         * @param string $sMinimumOxidVersionPe
         * @param string $sMinimumOxidVersionEe
         * @param string $sMinimumPhpVersion
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function __construct($sMinimumOxidVersionCe, $sMinimumOxidVersionPe, $sMinimumOxidVersionEe, $sMinimumPhpVersion)
        {
            $this->setMinimumOxidVersionCe($sMinimumOxidVersionCe);
            $this->setMinimumOxidVersionPe($sMinimumOxidVersionPe);
            $this->setMinimumOxidVersionEe($sMinimumOxidVersionEe);
            $this->setMinimumPhpVersion($sMinimumPhpVersion);
        }

        /**
         * @param string $sMinimumOxidVersionCe
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function setMinimumOxidVersionCe($sMinimumOxidVersionCe)
        {
            $this->_sMinimumOxidVersionCe = $sMinimumOxidVersionCe;
        }

        /**
         * @param string $sMinimumOxidVersionPe
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function setMinimumOxidVersionPe($sMinimumOxidVersionPe)
        {
            $this->_sMinimumOxidVersionPe = $sMinimumOxidVersionPe;
        }

        /**
         * @param string $sMinimumOxidVersionEe
         */
        public function setMinimumOxidVersionEe($sMinimumOxidVersionEe)
        {
            $this->_sMinimumOxidVersionEe = $sMinimumOxidVersionEe;
        }

        /**
         * @param string $sMinimumPhpVersion
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function setMinimumPhpVersion($sMinimumPhpVersion)
        {
            $this->_sMinimumPhpVersion = $sMinimumPhpVersion;
        }

        /**
         * @param $sOxidEdition
         * @param $sOxidVersion
         * @param $sPhpVersion
         *
         * @return bool
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function doesModuleVersionSatisfyInstalledVersion($sOxidEdition, $sOxidVersion, $sPhpVersion)
        {
            $blDoesSatisfy = false;

            switch (strtolower($sOxidEdition))
            {
                case "ce":
                    $blDoesSatisfy = $this->doesModuleVersionSatisfyInstalledVersionCe($sOxidVersion, $sPhpVersion);
                    break;

                case "pe":
                    $blDoesSatisfy = $this->doesModuleVersionSatisfyInstalledVersionPe($sOxidVersion, $sPhpVersion);
                    break;

                case "ee":
                    $blDoesSatisfy = $this->doesModuleVersionSatisfyInstalledVersionEe($sOxidVersion, $sPhpVersion);
                    break;

                default:
                    throw new InvalidArgumentException("Unknown oxid edition (" . $sOxidEdition . ")");
            }

            return $blDoesSatisfy;
        }

        /**
         * @return string
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getMinimumPhpVersion()
        {
            return $this->_sMinimumPhpVersion;
        }

        /**
         * @param $sOxidVersion
         * @param $sPhpVersion
         *
         * @return bool
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected function doesModuleVersionSatisfyInstalledVersionCe($sOxidVersion, $sPhpVersion)
        {
            return (version_compare($sOxidVersion, $this->getMinimumOxidVersionCe(), '>=') && version_compare($sPhpVersion, $this->getMinimumPhpVersion(), '>='));
        }

        /**
         * @return string
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getMinimumOxidVersionCe()
        {
            return $this->_sMinimumOxidVersionCe;
        }

        /**
         * @param $sOxidVersion
         * @param $sPhpVersion
         *
         * @return bool
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected function doesModuleVersionSatisfyInstalledVersionPe($sOxidVersion, $sPhpVersion)
        {
            return (version_compare($sOxidVersion, $this->getMinimumOxidVersionPe(), '>=') && version_compare($sPhpVersion, $this->getMinimumPhpVersion(), '>='));
        }

        /**
         * @return string
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getMinimumOxidVersionPe()
        {
            return $this->_sMinimumOxidVersionPe;
        }

        /**
         * @param $sOxidVersion
         * @param $sPhpVersion
         *
         * @return bool
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected function doesModuleVersionSatisfyInstalledVersionEe($sOxidVersion, $sPhpVersion)
        {
            return (version_compare($sOxidVersion, $this->getMinimumOxidVersionEe(), '>=') && version_compare($sPhpVersion, $this->getMinimumPhpVersion(), '>='));
        }

        /**
         * @return string
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getMinimumOxidVersionEe()
        {
            return $this->_sMinimumOxidVersionEe;
        }
    }