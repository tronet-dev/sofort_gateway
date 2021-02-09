<?php

namespace Tronet\Trosofortueberweisung\Application\Model;

/**
 * Model class TrosofortueberweisungRelease.
 *
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2018
 * @author        tronet GmbH
 *
 * @since         7.0.0
 * @version       8.0.9
 */
class TrosofortueberweisungRelease
{
    /**
     * @var string $_sModuleVersion
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    protected $_sModuleVersion;

    /**
     * @var string $_sDownloadLink
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    protected $_sDownloadLink;

    /**
     * @var TrosofortueberweisungRequirements $_oTroSofortueberweisungRequirements
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    protected $_oTroSofortueberweisungRequirements;

    /**
     * @var string $_sArchiveFileName
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    protected $_sArchiveFileName;

    /**
     * TrosofortueberweisungRelease constructor.
     *
     * @param string $sModuleVersion
     * @param string $sDownloadLink
     * @param string $sMinimumOxidVersionCe
     * @param string $sMinimumOxidVersionPe
     * @param string $sMinimumOxidVersionEe
     * @param string $sMinimumPhpVersion
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function __construct($sModuleVersion, $sDownloadLink, $sMinimumOxidVersionCe, $sMinimumOxidVersionPe, $sMinimumOxidVersionEe, $sMinimumPhpVersion)
    {
        $this->setTroModuleVersion($sModuleVersion);
        $this->setTroDownloadLink($sDownloadLink);

        $oTroSofortueberweisungRequirements = oxNew(TrosofortueberweisungRequirements::class, $sMinimumOxidVersionCe, $sMinimumOxidVersionPe, $sMinimumOxidVersionEe, $sMinimumPhpVersion);
        $this->setTroSofortgatewayReleaseRequirements($oTroSofortueberweisungRequirements);

        $sModuleVersionUnderscored = str_replace('.', '_', $this->getTroModuleVersion());
        
        $this->setTroArchiveFileName('Oxid-Sofortueberweisung-' . $sModuleVersionUnderscored . '.zip');
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
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function troDoesModuleVersionSatisfyInstalledVersion($sModuleVersion, $sOxidEdition, $sOxidVersion, $sPhpVersion)
    {
        return ($this->getTroSofortgatewayReleaseRequirements()->troDoesModuleVersionSatisfyInstalledVersion($sOxidEdition, $sOxidVersion, $sPhpVersion) && version_compare($sModuleVersion, $this->getTroModuleVersion(), '<'));
    }

    /**
     * Get the release version.
     *
     *
     * @return string
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.9
     */
    public function getTroModuleVersion()
    {
        return $this->_sModuleVersion;
    }

    /**
     * Set the version.
     *
     * @param string $sModuleVersion
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function setTroModuleVersion($sModuleVersion)
    {
        $this->_sModuleVersion = $sModuleVersion;
    }

    /**
     * @return TrosofortueberweisungRequirements
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function getTroSofortgatewayReleaseRequirements()
    {
        return $this->_oTroSofortueberweisungRequirements;
    }

    /**
     * @param TrosofortueberweisungRequirements $oTroSofortueberweisungRequirements
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function setTroSofortgatewayReleaseRequirements($oTroSofortueberweisungRequirements)
    {
        $this->_oTroSofortueberweisungRequirements = $oTroSofortueberweisungRequirements;
    }

    /**
     * Get the download url.
     *
     * @return string
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function getTroDownloadLink()
    {
        return $this->_sDownloadLink;
    }

    /**
     * Set the download url.
     *
     * @param string $sDownloadLink
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function setTroDownloadLink($sDownloadLink)
    {
        $this->_sDownloadLink = $sDownloadLink;
    }

    /**
     * @return string
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function getTroArchiveFileName()
    {
        return $this->_sArchiveFileName;
    }

    /**
     * @param string $sArchiveFileName
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function setTroArchiveFileName($sArchiveFileName)
    {
        $this->_sArchiveFileName = $sArchiveFileName;
    }
}
