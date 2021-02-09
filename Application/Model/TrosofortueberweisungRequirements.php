<?php

namespace Tronet\Trosofortueberweisung\Application\Model;

/**
 * Model class TrosofortueberweisungRequirements.
 *
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2018
 * @author        tronet GmbH
 *
 * @since         7.0.0
 * @version       8.0.0
 */
class TrosofortueberweisungRequirements
{
    /**
     * @var string $_sMinimumOxidVersionCe
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    protected $_sMinimumOxidVersionCe;

    /**
     * @var string $_sMinimumOxidVersionPe
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    protected $_sMinimumOxidVersionPe;

    /**
     * @var string $_sMinimumOxidVersionEe
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    protected $_sMinimumOxidVersionEe;

    /**
     * @var string $_sMinimumPhpVersion
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    protected $_sMinimumPhpVersion;

    /**
     * SofortReleaseRequirements constructor.
     *
     * @param string $sMinimumOxidVersionCe
     * @param string $sMinimumOxidVersionPe
     * @param string $sMinimumOxidVersionEe
     * @param string $sMinimumPhpVersion
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function __construct($sMinimumOxidVersionCe, $sMinimumOxidVersionPe, $sMinimumOxidVersionEe, $sMinimumPhpVersion)
    {
        $this->setTroMinimumOxidVersionCe($sMinimumOxidVersionCe);
        $this->setTroMinimumOxidVersionPe($sMinimumOxidVersionPe);
        $this->setTroMinimumOxidVersionEe($sMinimumOxidVersionEe);
        $this->setTroMinimumPhpVersion($sMinimumPhpVersion);
    }

    /**
     * @param string $sOxidEdition
     * @param string $sOxidVersion
     * @param string $sPhpVersion
     *
     * @return bool
     * @throws \InvalidArgumentException if an unknown oxid edition has been passed.
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function troDoesModuleVersionSatisfyInstalledVersion($sOxidEdition, $sOxidVersion, $sPhpVersion)
    {
        $blDoesSatisfy = false;

        switch (strtolower($sOxidEdition))
        {
            case 'ce':
                $blDoesSatisfy = $this->_troDoesModuleVersionSatisfyInstalledVersionCe($sOxidVersion, $sPhpVersion);
                break;

            case 'pe':
                $blDoesSatisfy = $this->_troDoesModuleVersionSatisfyInstalledVersionPe($sOxidVersion, $sPhpVersion);
                break;

            case 'ee':
                $blDoesSatisfy = $this->_troDoesModuleVersionSatisfyInstalledVersionEe($sOxidVersion, $sPhpVersion);
                break;

            default:
                throw new \InvalidArgumentException("Unknown oxid edition ({$sOxidEdition}). ");
        }

        return $blDoesSatisfy;
    }

    /**
     * @param string $sOxidVersion
     * @param string $sPhpVersion
     *
     * @return bool
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    protected function _troDoesModuleVersionSatisfyInstalledVersionCe($sOxidVersion, $sPhpVersion)
    {
        return (version_compare($sOxidVersion, $this->getTroMinimumOxidVersionCe(), '>=') && version_compare($sPhpVersion, $this->getTroMinimumPhpVersion(), '>='));
    }

    /**
     * @param string $sOxidVersion
     * @param string $sPhpVersion
     *
     * @return bool
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    protected function _troDoesModuleVersionSatisfyInstalledVersionPe($sOxidVersion, $sPhpVersion)
    {
        return (version_compare($sOxidVersion, $this->getTroMinimumOxidVersionPe(), '>=') && version_compare($sPhpVersion, $this->getTroMinimumPhpVersion(), '>='));
    }

    /**
     * @param string $sOxidVersion
     * @param string $sPhpVersion
     *
     * @return bool
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    protected function _troDoesModuleVersionSatisfyInstalledVersionEe($sOxidVersion, $sPhpVersion)
    {
        return (version_compare($sOxidVersion, $this->getTroMinimumOxidVersionEe(), '>=') && version_compare($sPhpVersion, $this->getTroMinimumPhpVersion(), '>='));
    }

    /**
     * @return string
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function getTroMinimumOxidVersionCe()
    {
        return $this->_sMinimumOxidVersionCe;
    }

    /**
     * @param string $sMinimumOxidVersionCe
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function setTroMinimumOxidVersionCe($sMinimumOxidVersionCe)
    {
        $this->_sMinimumOxidVersionCe = $sMinimumOxidVersionCe;
    }

    /**
     * @return string
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function getTroMinimumOxidVersionPe()
    {
        return $this->_sMinimumOxidVersionPe;
    }

    /**
     * @param string $sMinimumOxidVersionPe
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function setTroMinimumOxidVersionPe($sMinimumOxidVersionPe)
    {
        $this->_sMinimumOxidVersionPe = $sMinimumOxidVersionPe;
    }

    /**
     * @return string
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function getTroMinimumOxidVersionEe()
    {
        return $this->_sMinimumOxidVersionEe;
    }

    /**
     * @param string $sMinimumOxidVersionEe
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function setTroMinimumOxidVersionEe($sMinimumOxidVersionEe)
    {
        $this->_sMinimumOxidVersionEe = $sMinimumOxidVersionEe;
    }

    /**
     * @return string
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function getTroMinimumPhpVersion()
    {
        return $this->_sMinimumPhpVersion;
    }

    /**
     * @param string $sMinimumPhpVersion
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function setTroMinimumPhpVersion($sMinimumPhpVersion)
    {
        $this->_sMinimumPhpVersion = $sMinimumPhpVersion;
    }
}
