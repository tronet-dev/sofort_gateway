<?php

namespace Tronet\Trosofortueberweisung\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\UtilsView;
use Tronet\Trosofortueberweisung\Application\Model\TrosofortueberweisungRelease;
use Tronet\Trosofortueberweisung\Application\Model\TrosofortueberweisungReleaseList;
use Tronet\Trosofortueberweisung\Core\SofortConfiguration;

/**
 * Backend Navigation controller. Mainly used to check for updates for new SOFORT packages.
 *
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2018
 * @author        tronet GmbH
 *
 * @since         7.0.0
 * @version       8.0.9
 */
class TrosofortueberweisungNavigationController extends TrosofortueberweisungNavigationController_parent
{
    /**
     * @var SofortConfiguration $_oSofortConfiguration
     *
     * @author        tronet GmbH
     * @since         7.0.0
     * @version       8.0.0
     */
    protected $_oSofortConfiguration = null;

    /**
     * Adds a simple check for the newest version of SOFORT for the used PHP-version.
     *
     * @return string
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function render()
    {
        $sReturn = parent::render();

        $sItem = $this->getConfig()->getRequestParameter('item');
        $sItem = ($sItem ? basename($sItem) : false);

        if ($sItem === 'home.tpl' && !$this->getConfig()->getRequestParameter('navReload'))
        {
            $this->_troCheckSOFORTUpdates();
        }
        else
        {
            $this->getSession()->deleteVariable('navReload');
        }

        return $sReturn;
    }

    /**
     * Function to lookup the newest version of SOFORT stored in an XML-file.
     * The function compares the installed shop-version with the newest version available.
     * If there is a newer version available, a message for the frontend is added.
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    protected function _troCheckSOFORTUpdates()
    {
        if ($this->getConfig()->getConfigParam('blTroGateWayUpdateCheck'))
        {
            try
            {
                $oTrosofortueberweisungRelease = $this->_getTroLatestRelease(
                    $this->getTroCurrentModuleVersion(),
                    $this->getConfig()->getEdition(),
                    $this->getConfig()->getVersion(),
                    PHP_VERSION
                );

                if ($oTrosofortueberweisungRelease instanceof TrosofortueberweisungRelease)
                {
                    $aViewData['trosofortueberweisung_update_notification'] = $this->_troRenderUpdateNotificationMessage($oTrosofortueberweisungRelease);
                }
            }
            catch (\Exception $oException)
            {
                // silently ignore as no error messages shall be displayed on the OXID eShop dashboard.
            }

            $this->addTplParam('aMessage', $aViewData);
        }
    }

    /**
     * @param string $sModuleVersion
     * @param string $sOxidEdition
     * @param string $sOxidVersion
     * @param string $sPhpVersion
     *
     * @return TrosofortueberweisungRelease
     *
     * @throws \InvalidArgumentException
     *
     * @author  tronet GmbH
     * @since   8.0.9
     * @version 8.0.9
     */
    protected function _getTroLatestRelease($sModuleVersion, $sOxidEdition, $sOxidVersion, $sPhpVersion)
    {
        $oTrosofortueberweisungReleaseList = $this->_getTroReleaseListFromUrl($this->getTroSOFORTConfig()->getTroReleaseListUrl());

        return $oTrosofortueberweisungReleaseList->getTroLatestRelease($sModuleVersion, $sOxidEdition, $sOxidVersion, $sPhpVersion);
    }

    /**
     * @param string $sUrl
     *
     * @return TrosofortueberweisungReleaseList
     *
     * @throws \InvalidArgumentException
     *
     * @author  tronet GmbH
     * @since   8.0.9
     * @version 8.0.9
     */
    protected function _getTroReleaseListFromUrl($sUrl)
    {
        $oTrosofortueberweisungReleaseList = oxNew(TrosofortueberweisungReleaseList::class);
        $oTrosofortueberweisungReleaseList->troSelectXmlUri($sUrl);

        return $oTrosofortueberweisungReleaseList;
    }

    /**
     * @return SofortConfiguration
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function getTroSOFORTConfig()
    {
        if (($this->_oSofortConfiguration instanceof SofortConfiguration) === false)
        {
            $this->_oSofortConfiguration = oxNew(SofortConfiguration::class);
        }

        return $this->_oSofortConfiguration;
    }

    /**
     * Simple getter function to retrieve the currently installed Module-Version.
     *
     * @return string The current version of SOFORT Überweisung
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public function getTroCurrentModuleVersion()
    {
        $aModuleVersions = $this->getConfig()->getConfigParam('aModuleVersions');

        return $aModuleVersions['trosofortueberweisung'];
    }

    /**
     * Renders notification message for passed release.
     *
     * @param TrosofortueberweisungRelease $oTrosofortueberweisungRelease
     *
     * @return mixed|string
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    protected function _troRenderUpdateNotificationMessage($oTrosofortueberweisungRelease)
    {
        $oSmarty = Registry::get(UtilsView::class)->getSmarty();
        $oSmarty->assign('oView', $this);
        $oSmarty->assign('oViewConf', $this->getViewConfig());

        $oSmarty->assign('sTroDownloadLink', $oTrosofortueberweisungRelease->getTroDownloadLink());
        $oSmarty->assign('sTroChangeLogLink', $this->getTroSOFORTConfig()->getTroChangeLogUrl());
        $oSmarty->assign('aTroNewVersion', [$oTrosofortueberweisungRelease->getTroModuleVersion()]);

        return $oSmarty->fetch('trosofortueberweisung_updateavailable.tpl');
    }

    /**
     * @param SofortConfiguration $oSofortConfiguration
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    protected function _setTroSOFORTConfig($oSofortConfiguration)
    {
        $this->_oSofortConfiguration = $oSofortConfiguration;
    }
}
    