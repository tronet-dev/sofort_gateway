<?php

    /**
     * Main admin controller for SOFORT Banking by tronet.
     *
     * @file          trosofortueberweisungreleaseoxlist.php
     * @link          http://www.tro.net
     * @copyright (C) tronet GmbH 2017
     * @package       modules
     * @addtogroup    application/controllers/admin
     * @author        tronet GmbH
     * @since         7.0.0
     */
    class trosofortueberweisungreleaseoxlist extends trosofortueberweisungreleaseoxlist_parent
    {
        /**
         * trosofortueberweisungreleaseoxlist constructor.
         *
         * @param string $sObjectName
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function __construct($sObjectName = 'trosofortueberweisungrelease')
        {
            parent::__construct($sObjectName);
        }

        /**
         * Selects data from specified xml uri.
         *
         * @param string $sXmlUri Uri to a valid xml file.
         *
         * @author tronet GmbH
         * @since  7.0.0
         * @throws InvalidArgumentException
         */
        public function troSelectXmlUri($sXmlUri)
        {
            try
            {
                list($status) = get_headers($sXmlUri);
                if (strpos($status, '200') == false)
                {
                    throw new RuntimeException('Xml uri (' . $sXmlUri . ') is not accessable (status: ' . $status . ').');
                }

                $oXml = new \SimpleXMLElement($sXmlUri, null, true);
                foreach ($oXml->releases->release as $oSOFORTReleaseXml)
                {
                    $this->_aArray[] = oxNew($this->_sObjectsInListName, $oSOFORTReleaseXml->version, $oSOFORTReleaseXml->download, $oSOFORTReleaseXml->requirements->minimumOxidVersion->ce, $oSOFORTReleaseXml->requirements->minimumOxidVersion->pe, $oSOFORTReleaseXml->requirements->minimumOxidVersion->ee, $oSOFORTReleaseXml->requirements->minimumPhpVersion);
                }
            }
            catch (Exception $oException)
            {
                throw new InvalidArgumentException('Could not process passed xml uri (' . $sXmlUri . ') properly.', 0, $oException);
            }
        }

        /**
         * Finds and returns the latest release for system as defined by the parameters.
         *
         * @param string $sModuleVersion
         * @param string $sOxidEdition
         * @param string $sOxidVersion
         * @param string $sPhpVersion
         *
         * @return null|trosofortueberweisungrelease
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function troGetLatestRelease($sModuleVersion, $sOxidEdition, $sOxidVersion, $sPhpVersion)
        {
            $oLatestRelease = null;

            /** @var trosofortueberweisungrelease $oSOFORTRelease */
            foreach ($this->_aArray as $oSOFORTRelease)
            {
                if ($oSOFORTRelease->doesModuleVersionSatisfyInstalledVersion($sModuleVersion, $sOxidEdition, $sOxidVersion, $sPhpVersion))
                {
                    $oLatestRelease = $oSOFORTRelease;
                }
            }

            return $oLatestRelease;
        }

        /**
         * Finds and returns specified release. In case the version is in a different
         * format it can be specified as well.
         *
         * @param      $sVersion
         * @param null $sFormat
         *
         * @return null|trosofortueberweisungrelease
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function troGetRelease($sVersion, $sFormat = null)
        {
            $oRelease = null;

            /** @var trosofortueberweisungrelease $oSOFORTRelease */
            foreach ($this->_aArray as $oSOFORTRelease)
            {
                if ($oSOFORTRelease->getVersion($sFormat) == $sVersion)
                {
                    $oRelease = $oSOFORTRelease;
                    break;
                }
            }

            return $oRelease;
        }
    }