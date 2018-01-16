<?php

    namespace Tronet\Trosofortueberweisung\Application\Model;

    use OxidEsales\Eshop\Application\Model\ListObject;

    /**
     * Main admin controller for SOFORT Banking by tronet.
     *
     * @link          http://www.tro.net
     * @copyright (c) tronet GmbH 2017
     * @author        tronet GmbH
     *
     * @since         7.0.0
     * @version       8.0.0
     *
     * @property array  $_aArray
     */
    class TrosofortueberweisungReleaseList
    {
        /**
         * @var array $_aArray
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        protected $_aArray;

        /**
         * @return mixed
         * @version 7.0.2
         * @since   7.0.2
         * @author  tronet GmbH
         */
        public function getArray()
        {
            return $this->_aArray;
        }

        /**
         * @param mixed $aArray
         *
         * @version 7.0.2
         * @since   7.0.2
         * @author  tronet GmbH
         */
        public function setArray($aArray)
        {
            $this->_aArray = $aArray;
        }

        /**
         * Selects data from specified xml uri.
         *
         * @param string $sXmlUri Uri to a valid xml file.
         *
         * @throws \InvalidArgumentException
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function troSelectXmlUri($sXmlUri)
        {
            try
            {
                list($iStatus) = get_headers($sXmlUri);
                if (strpos($iStatus, '200') === false)
                {
                    throw new \RuntimeException('Xml uri (' . $sXmlUri . ') is not accessible (status: ' . $iStatus . ').');
                }

                $oSimpleXMLElement = new \SimpleXMLElement($sXmlUri, null, true);
                if ($oSimpleXMLElement->releases->release)
                {
                    foreach ($oSimpleXMLElement->releases->release as $oSOFORTReleaseXml)
                    {
                        $this->_aArray[] = oxNew(
                            TrosofortueberweisungRelease::class,
                            $oSOFORTReleaseXml->version,
                            $oSOFORTReleaseXml->download,
                            $oSOFORTReleaseXml->requirements->minimumOxidVersion->ce,
                            $oSOFORTReleaseXml->requirements->minimumOxidVersion->pe,
                            $oSOFORTReleaseXml->requirements->minimumOxidVersion->ee,
                            $oSOFORTReleaseXml->requirements->minimumPhpVersion
                        );
                    }
                }
            }
            catch (\Exception $oException)
            {
                throw new \InvalidArgumentException('Could not process passed xml uri (' . $sXmlUri . ') properly.', 0, $oException);
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
         * @return null|TrosofortueberweisungRelease
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function getTroLatestRelease($sModuleVersion, $sOxidEdition, $sOxidVersion, $sPhpVersion)
        {
            $oLatestRelease = null;

            if (is_array($this->_aArray))
            {
                foreach ($this->_aArray as $oSOFORTRelease)
                {
                    if ($oSOFORTRelease->troDoesModuleVersionSatisfyInstalledVersion($sModuleVersion, $sOxidEdition, $sOxidVersion, $sPhpVersion))
                    {
                        $oLatestRelease = $oSOFORTRelease;
                    }
                }
            }

            return $oLatestRelease;
        }

        /**
         * Finds and returns specified release. In case the version is in a different
         * format it can be specified as well.
         *
         * @param string $sModuleVersion
         * @param null   $sFormat
         *
         * @return null|TrosofortueberweisungRelease
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function getTroRelease($sModuleVersion, $sFormat = null)
        {
            $oTrosofortueberweisungRelease = null;

            if (is_array($this->_aArray))
            {
                foreach ($this->_aArray as $oSOFORTRelease)
                {
                    if ($oSOFORTRelease->getTroModuleVersion($sFormat) === $sModuleVersion)
                    {
                        $oTrosofortueberweisungRelease = $oSOFORTRelease;
                        break;
                    }
                }
            }

            return $oTrosofortueberweisungRelease;
        }
    }
