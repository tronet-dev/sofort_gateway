<?php

    /**
     * Backend Update controller used for the update process.
     *
     * @file          trosofortueberweisung_update.php
     * @link          http://www.tro.net
     * @copyright (C) tronet GmbH 2018
     * @package       modules
     * @addtogroup    application/controllers/admin
     * @author        tronet GmbH
     * @since         7.0.0
     */
    class trosofortueberweisung_update extends oxAdminView
    {
        /**
         * @var trosofortueberweisungreleaselist $_oSOFORTReleaseList
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_oSOFORTReleaseList;

        /**
         * @var trosofortueberweisungdirectoryutility $_oSOFORTDirectoryUtility
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_oSOFORTDirectoryUtility = null;

        /**
         * Additional rendering actions for update view.
         *
         * @return string Template to render
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function render()
        {
            parent::render();

            $this->addTplParam('sTroShopMainUrl', $this->getConfig()->getShopMainUrl());

            $this->addTplParam('sTroSessionToken', $this->getSession()->getSessionChallengeToken());

            $sReleaseVersionRaw = $this->getConfig()->getRequestParameter('trosofortueberweisung_version');
            $this->addTplParam('sTroNewVersionRaw', $sReleaseVersionRaw);

            $sReleaseVersionForDisplay = str_replace('_', '.', $sReleaseVersionRaw);
            $this->addTplParam('sTroNewVersion', $sReleaseVersionForDisplay);

            $this->addTplParam('aTroNewVersion', array($sReleaseVersionForDisplay));
            $sTroTemplate = 'trosofortueberweisung_update.tpl';

            return $sTroTemplate;
        }

        /**
         * Action method checking whether core files has been modified by anyone but
         * module developer.
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function troChangedModuleCoreFiles()
        {
            $oSOFORTConfig = oxNew('trosofortueberweisungconfig');
            $sDownloadLinkRaw = $oSOFORTConfig->getTroMetaHashLinkRaw();
            $sDownloadFile = sprintf($sDownloadLinkRaw, $this->getCurrentVersion());

            $oXML = file_get_contents($sDownloadFile);
            if ($oXML)
            {
                $oXml = new \SimpleXMLElement($oXML);
                $sModulePath = getShopBasePath() . 'modules/';
                $aChangedFiles = $this->getSOFORTDirectoryUtility()->getChangedFilesFromDirectory($sModulePath, $oXml);
                $this->_troRenderJson(json_encode($aChangedFiles, JSON_FORCE_OBJECT));
            }
            else
            {
                $sErrorMessage = oxRegistry::getLang()->translateString('TRO_SOFORT_UPDATE_JSON_RETURN_couldNotFetchXml');
                $this->_troRenderJson('{"couldNotFetchXml":"' . $sErrorMessage . '"}');
            }
        }

        /**
         * Simple getter function to retrieve the currently installed Module-Version.
         *
         * @return string Current version of SOFORT Überweisung
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getCurrentVersion()
        {
            $aModuleVersions = $this->getConfig()->getConfigParam('aModuleVersions');

            return $aModuleVersions["trosofortueberweisung"];
        }

        public function getSOFORTDirectoryUtility()
        {
            if ($this->_oSOFORTDirectoryUtility == null)
            {
                $this->_oSOFORTDirectoryUtility = oxNew('trosofortueberweisungdirectoryutility');
            }

            return $this->_oSOFORTDirectoryUtility;
        }

        /**
         * Outputs json data and terminates the script afterwards.
         *
         * @param string $sJson Valid json data
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected function _troRenderJson($sJson)
        {
            header("Expires: Mon, 3 May 2000 01:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Content-type: json");
            echo json_encode($sJson);
            exit;
        }

        /**
         * Action method downloading latest release for current OXID eShop. Latest release
         * is defined by the get parameter "trosofortueberweisung_version".
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function troDownloadLatestModuleRelease()
        {
            $oSOFORTRelease = $this->getSOFORTReleaseList()->troGetRelease($this->getConfig()->getRequestParameter('trosofortueberweisung_version'), '_');

            if ($oSOFORTRelease != null)
            {
                $oSOFORTUpdateUtility = oxNew('trosofortueberweisungupdateutility');

                if ($oSOFORTUpdateUtility->downloadRelease($oSOFORTRelease) != false)
                {
                    $this->_troRenderJson('{}');
                }
                else
                {
                    $sErrorMessage = oxRegistry::getLang()->translateString('TRO_SOFORT_UPDATE_JSON_RETURN_fileNotDownloaded');
                    $this->_troRenderJson('{"fileNotDownloaded":"' . $sErrorMessage . '"}');
                }
            }
            else
            {
                $sVersion = str_replace('_', '.', $this->getConfig()->getRequestParameter('trosofortueberweisung_version'));
                $sErrorMessageRaw = oxRegistry::getLang()->translateString('TRO_SOFORT_UPDATE_JSON_RETURN_releaseNotFound');
                $sErrorMessage = sprintf($sErrorMessageRaw, $sVersion);
                $this->_troRenderJson('{"releaseNotFound":"' . $sErrorMessage . '"}');
            }
        }

        /**
         * Getter for _oSOFORTReleaseList.
         *
         * @return trosofortueberweisungreleaselist
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getSOFORTReleaseList()
        {
            if ($this->_oSOFORTReleaseList == null)
            {
                $oSOFORTConfig = oxNew('trosofortueberweisungconfig');
                $this->_oSOFORTReleaseList = $oSOFORTReleaseList = oxNew('trosofortueberweisungreleaselist');
                $this->_oSOFORTReleaseList->troSelectXmlUri($oSOFORTConfig->getReleaseListUrl());
            }

            return $this->_oSOFORTReleaseList;
        }

        /**
         * Action method extracting latest module release. Latest release
         * is defined by the get parameter "trosofortueberweisung_version".
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function troExtractLatestModuleRelease()
        {
            $oSOFORTRelease = $this->getSOFORTReleaseList()->troGetRelease($this->getConfig()->getRequestParameter('trosofortueberweisung_version'), '_');

            if ($oSOFORTRelease != null)
            {
                $oSOFORTUpdateUtility = oxNew('trosofortueberweisungupdateutility');

                $sExtractDirectory = $this->getConfig()->getConfigParam('sShopDir') . 'tmp/' . $oSOFORTRelease->getArchiveName();
                $sExtractIntoDirectory = $this->getConfig()->getConfigParam('sShopDir') . 'tmp/';

                $blSuccess = $oSOFORTUpdateUtility->extractArchive($sExtractDirectory, $sExtractIntoDirectory, false);
                if ($blSuccess)
                {
                    $this->_troRenderJson('{}');
                }
                else
                {
                    $sErrorMessage = oxRegistry::getLang()->translateString('TRO_SOFORT_UPDATE_JSON_RETURN_unknownResult');
                    $this->_troRenderJson('{"unknownResult":"' . $sErrorMessage . '"}');
                }
            }
            else
            {
                $sErrorMessage = oxRegistry::getLang()->translateString('TRO_SOFORT_UPDATE_JSON_RETURN_unknownResult');
                $this->_troRenderJson('{"unknownResult":"' . $sErrorMessage . '"}');
            }
        }

        /**
         * Action method creating a module backup of current module tronet/trosofortueberweisung.
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function troCreateModuleBackup()
        {
            $oSOFORTUpdateUtility = oxNew('trosofortueberweisungupdateutility');
            $sFile = $oSOFORTUpdateUtility->createModuleBackup();
            $blFileExists = file_exists($sFile);

            if ($blFileExists)
            {
                $this->_troRenderJson('{"couldNotCreateBackup":"0"}');
            }
            else
            {
                $sErrorMessage = oxRegistry::getLang()->translateString('TRO_SOFORT_UPDATE_JSON_RETURN_unknownResult');
                $this->_troRenderJson('{"couldNotCreateBackup":"1","unknownResult":"' . $sErrorMessage . '"}');
            }
        }

        /**
         * Action method performing the actual update by replacing the current module directory
         * with the recently downloaded release.
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function troPerformUpdate()
        {
            $oSOFORTRelease = $this->getSOFORTReleaseList()->troGetRelease($this->getConfig()->getRequestParameter('trosofortueberweisung_version'), '_');

            if ($oSOFORTRelease != null)
            {
                $oSOFORTUpdateUtility = oxNew('trosofortueberweisungupdateutility');
                $sShopHomeDir = $this->getConfig()->getConfigParam('sShopDir');
                $sSourceDirectory = $sShopHomeDir . 'tmp/' . $oSOFORTRelease->getExtractDirectoryInZip();
                if ($oSOFORTUpdateUtility->copyDirectory($sSourceDirectory, $sShopHomeDir))
                {
                    $this->_troRenderJson('{}');
                }
                else
                {
                    $sErrorMessage = oxRegistry::getLang()->translateString('TRO_SOFORT_UPDATE_JSON_RETURN_copyFailed');
                    $this->_troRenderJson('{"copyFailed":"' . $sErrorMessage . '"}');
                }
            }
            else
            {
                $sErrorMessage = oxRegistry::getLang()->translateString('TRO_SOFORT_UPDATE_JSON_RETURN_unknownResult');
                $this->_troRenderJson('{"unknownResult":"' . $sErrorMessage . '"}');
            }
        }

        /**
         * Action method that reactivates the module so that changes of the new module version are applied.
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function troRefreshModule()
        {
            if ($this->_refreshModule())
            {
                $this->_troRenderJson('{}');
            }
            else
            {
                $sErrorMessage = oxRegistry::getLang()->translateString('TRO_SOFORT_UPDATE_JSON_RETURN_unknownResult');
                $this->_troRenderJson('{"unknownResult":"' . $sErrorMessage . '"}');
            }
        }

        /**
         * Function to (de)activate the module to apply changes from the metadata.
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        private function _refreshModule()
        {
            $oModule = oxNew('oxModule');
            $oModule->load("trosofortueberweisung");

            $sEdition = oxRegistry::getConfig()->getEdition();
            $sMinVersion = (($sEdition == "EE") ? "5.2" : "4.9.0");

            // check if we are at least 4.9 (or 5.2 in EE), otherwise we need another routine
            if (version_compare(oxRegistry::getConfig()->getVersion(), $sMinVersion, ">="))
            {
                $oModuleCache = oxNew('oxModuleCache', $oModule);
                $oModuleInstaller = oxNew('oxModuleInstaller', $oModuleCache);
                $blRefreshingSuccessful = ($oModuleInstaller->deactivate($oModule) && $oModuleInstaller->activate($oModule));
            }
            else
            {
                $blRefreshingSuccessful = ($oModule->deactivate() && $oModule->activate());
            }

            $this->_aViewData["updatenav"] = "1";
            return $blRefreshingSuccessful;
        }

        /**
         * Action method clearing the OXID tmp/* directory.
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function troClearOxidTmpDirectory()
        {
            $oSOFORTUpdateUtility = oxNew('trosofortueberweisungupdateutility');
            $oSOFORTUpdateUtility->clearOxidTmpDirectory();
            $this->_troRenderJson('{}');
        }

        /**
         * Setter for _oSOFORTReleaseList.
         *
         * @param $oSOFORTReleaseList
         *
         * @return string
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function setSOFORTReleaseList($oSOFORTReleaseList)
        {
            $this->_oSOFORTReleaseList = $oSOFORTReleaseList;
        }

        public function setSOFORTDirectoryUtility($oSOFORTDirectoryUtility)
        {
            $this->_oSOFORTDirectoryUtility = $oSOFORTDirectoryUtility;
        }
    }