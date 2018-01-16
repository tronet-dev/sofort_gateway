<?php

    namespace Tronet\Trosofortueberweisung\Application\Controller\Admin;

    use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
    use OxidEsales\Eshop\Core\Registry;
    use OxidEsales\Eshop\Core\UtilsView;
    use Tronet\Trosofortueberweisung\Application\Model\TrosofortueberweisungRelease;
    use Tronet\Trosofortueberweisung\Application\Model\TrosofortueberweisungReleaseList;
    use Tronet\Trosofortueberweisung\Core\SofortConfiguration;
    use Tronet\Trosofortueberweisung\Core\Utility\DirectoryUtility;
    use Tronet\Trosofortueberweisung\Core\Utility\CurlUtility;

    /**
     * Main admin controller for SOFORT Banking by tronet.
     *
     * This backend controller provides a UI for following
     * business processes:
     *      * manual check for new versions
     *      * manual check if and what files has been adjusted
     *
     * @link          http://www.tro.net
     * @copyright (c) tronet GmbH 2017
     * @author        tronet GmbH
     *
     * @since         7.0.0
     * @version       8.0.0
     */
    class TrosofortueberweisungMainController extends AdminController
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
         * @var DirectoryUtility $_oSofortDirectoryUtility
         * 
         * @author        tronet GmbH
         * @since         7.0.0
         * @version       8.0.0
         */
        protected $_oSofortDirectoryUtility = null;

        /**
         * @var ThisTemplate $_sThisTemplate
         * 
         * @author        tronet GmbH
         * @since         8.0.0
         * @version       8.0.0
         */
        protected $_sThisTemplate = 'trosofortueberweisung_main.tpl';       

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

        /**
         * @return DirectoryUtility
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function getTroSOFORTDirectoryUtility()
        {
            if ($this->_oSofortDirectoryUtility === null)
            {
                $this->_oSofortDirectoryUtility = oxNew(DirectoryUtility::class);
            }

            return $this->_oSofortDirectoryUtility;
        }

        /**
         * @param DirectoryUtility $directoryUtility
         *
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        protected function _setTroSOFORTDirectoryUtility($oDirectoryUtility)
        {
            $this->_oSofortDirectoryUtility = $oDirectoryUtility;
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
         * Action function that checks for module updates.
         *
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function troCheckForUpdates()
        {
            $this->_troCheckForModuleUpdates();
            $this->addTplParam('blTroCheckedForUpdates', true);
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
        protected function _troCheckForModuleUpdates()
        {
            try
            {
                $oTrosofortueberweisungReleaseList = oxNew(TrosofortueberweisungReleaseList::class);
                $oTrosofortueberweisungReleaseList->troSelectXmlUri($this->getTroSOFORTConfig()->getTroReleaseListUrl());

                $sModuleVersion = $this->getTroCurrentModuleVersion();
                $sOxidEdition = $this->getConfig()->getEdition();
                $sOxidVersion = $this->getConfig()->getVersion();

                $oTrosofortueberweisungRelease = $oTrosofortueberweisungReleaseList->getTroLatestRelease($sModuleVersion, $sOxidEdition, $sOxidVersion, PHP_VERSION);

                if ($oTrosofortueberweisungRelease instanceof TrosofortueberweisungRelease)
                {
                    $aViewData['trosofortueberweisung_update_notification'] = $this->_troRenderUpdateNotificationMessage($oTrosofortueberweisungRelease);
                }
                else
                {
                    $aViewData['trosofortueberweisung_update_notification'] = $this->_troRenderUpdateNotificationMessageLatestVersionInstalled();
                }
            }
            catch (\Exception $oException)
            {
                $aViewData['trosofortueberweisung_update_notification'] = $this->_troRenderUpdateNotificationFailedMessage();
            }
            
            $this->addTplParam('aMessage', $aViewData);
        }


        /**
         * Action function that checks for module changes.
         *
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function troCheckForChanges()
        {
            $sTroCheckedChangesFailedMessage = '';
            $oSofortConfiguration = oxNew(SofortConfiguration::class);
            $sDownloadLinkRaw = $oSofortConfiguration->getTroMetaHashLinkRaw();
            $sDownloadLinkFinal = sprintf($sDownloadLinkRaw, $this->getTroCurrentModuleVersion());

            if ($this->_troUrlExists($sDownloadLinkFinal))
            {
                $oSOFORTcURL = oxNew(CurlUtility::class, $sDownloadLinkFinal);
                $oSOFORTcURL->troCurlSetOpt(CURLOPT_RETURNTRANSFER, 1);
                $oReleaseListXml = $oSOFORTcURL->troCurlExec();
                $oSOFORTcURL->troCloseCh();
                
                if ($oReleaseListXml)
                {
                    $oSimpleXMLElement = new \SimpleXMLElement($oReleaseListXml);
                    $sModulePath = getShopBasePath() . 'modules/';
                    $aChangedFiles = $this->getTroSOFORTDirectoryUtility()->getTroChangedFilesFromDirectory($sModulePath, $oSimpleXMLElement);
                    $blTroCheckedChangesFailed = false;
                }
                else
                {
                    if ($oSOFORTcURL->getTroErrorNumber() > 0)
                    {
                        $aErrorCodes = $oSOFORTcURL->getTroErrorCodes();
                        $sErrorCodeName = $aErrorCodes[$oSOFORTcURL->getTroErrorNumber()];
                        $sLanguageKey = 'TRO_SOFORT_' . $sErrorCodeName;
                        $sTroCheckedChangesFailedMessage = \OxidEsales\Eshop\Core\Registry::getLang()->translateString($sLanguageKey);
                    }
                    $aChangedFiles = ['changedCoreFiles' => 0];
                    $blTroCheckedChangesFailed = true;
                }
            }
            else
            {
                $sTroCheckedChangesFailedMessage = \OxidEsales\Eshop\Core\Registry::getLang()->translateString('TRO_SOFORT_CURLE_COULDNT_CONNECT');
                $aChangedFiles = ['changedCoreFiles' => 0];
                $blTroCheckedChangesFailed = true;
            }

            $this->addTplParam('aTroChangedFiles', $aChangedFiles);
            $this->addTplParam('blTroCheckedChanges', true);
            $this->addTplParam('blTroCheckedChangesFailed', $blTroCheckedChangesFailed);
            $this->addTplParam('sTroCheckedChangesFailedMessage', $sTroCheckedChangesFailedMessage);
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
         * Renders notification message for passed release.
         *
         * @return mixed|string
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        protected function _troRenderUpdateNotificationMessageLatestVersionInstalled()
        {
            $oSmarty = Registry::get(UtilsView::class)->getSmarty();
            $oSmarty->assign('oView', $this);
            $oSmarty->assign('oViewConf', $this->getViewConfig());

            return $oSmarty->fetch('trosofortueberweisung_noupdateavailable.tpl');
        }

        /**
         * Renders notification message for passed release.
         *
         * @param \Exception $exception
         *
         * @return mixed|string
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        protected function _troRenderUpdateNotificationFailedMessage($exception = null)
        {
            $oSmarty = Registry::get(UtilsView::class)->getSmarty();
            $oSmarty->assign('oView', $this);
            $oSmarty->assign('oViewConf', $this->getViewConfig());

            return $oSmarty->fetch('trosofortueberweisung_updateavailable_failed.tpl');
        }

        /**
         * Check whether $sUrl is exists.
         *
         * @param string $sUrl Url to check
         *
         * @return bool $blUrlExists
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        protected function _troUrlExists($sUrl)
        {
            $blUrlExists = true;
            $aFileHeaders = @get_headers($sUrl);
            if ($aFileHeaders[0] === 'HTTP/1.1 404 Not Found')
            {
                $blUrlExists = false;
            }

            return $blUrlExists;
        }
    }
