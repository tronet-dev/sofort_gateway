<?php

    /**
     * Main admin controller for SOFORT Banking by tronet.
     *
     * This backend controller provides a UI for following
     * business processes:
     *      * manual check for new versions
     *      * manual check if and what files has been adjusted
     *
     * @file          trosofortueberweisung_main.php
     * @link          http://www.tro.net
     * @copyright (C) tronet GmbH 2018
     * @package       modules
     * @addtogroup    application/controllers/admin
     * @author        tronet GmbH
     * @since         7.0.0
     */
    class trosofortueberweisung_main extends oxAdminView
    {
        /**
         * @var trosofortueberweisungconfig $_oSOFORTConfig
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_oSOFORTConfig = null;

        /**
         * @var trosofortueberweisungdirectoryutility $_oSOFORTDirectoryUtility
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_oSOFORTDirectoryUtility = null;

        /**
         * Extends rendering process by our needs.
         *
         * @return string
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function render()
        {
            parent::render();
            return "trosofortueberweisung_main.tpl";
        }

        /**
         * Action function that checks for module updates.
         *
         * @return string
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function troCheckForUpdates()
        {
            $this->_checkSOFORTUpdates();
            $this->addTplParam('blTroCheckedForUpdates', true);
        }

        /**
         * Function to lookup the newest version of SOFORT stored in an XML-file.
         * The function compares the installed shop-version with the newest version available.
         * If there is a newer version available, a message for the frontend is added.
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        private function _checkSOFORTUpdates()
        {
            try
            {
                $oSOFORTReleaseList = oxNew('trosofortueberweisungreleaselist');
                $oSOFORTReleaseList->troSelectXmlUri($this->getSOFORTConfig()->getReleaseListUrl());

                $sModuleVersion = $this->getCurrentModuleVersion();
                $sOxidEdition = $this->getConfig()->getEdition();
                $sOxidVersion = $this->getConfig()->getVersion();

                $oSOFORTRelease = $oSOFORTReleaseList->troGetLatestRelease($sModuleVersion, $sOxidEdition, $sOxidVersion, PHP_VERSION);

                if ($oSOFORTRelease != null)
                {
                    $aViewData['aMessage']['trosofortueberweisung_update_notification'] = $this->_renderUpdateNotificationMessage($oSOFORTRelease);
                    $this->addTplParam('aTroMessage', $aViewData);
                }
                else
                {
                    $aViewData['aMessage']['trosofortueberweisung_update_notification'] = $this->_renderUpdateNotificationMessageLatestVersionInstalled();
                    $this->addTplParam('aTroMessage', $aViewData);
                }
            }
            catch (Exception $oException)
            {
                $aViewData['aMessage']['trosofortueberweisung_update_notification'] = $this->_renderUpdateNotificationFailedMessage($oException);
                $this->addTplParam('aTroMessage', $aViewData);
            }
        }

        /**
         * @return trosofortueberweisungconfig
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getSOFORTConfig()
        {
            if ($this->_oSOFORTConfig == null)
            {
                $this->_oSOFORTConfig = oxNew('trosofortueberweisungconfig');
            }

            return $this->_oSOFORTConfig;
        }

        /**
         * Simple getter function to retrieve the currently installed Module-Version.
         *
         * @return string The current version of SOFORT Überweisung
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getCurrentModuleVersion()
        {
            $aModuleVersions = $this->getConfig()->getConfigParam('aModuleVersions');

            return $aModuleVersions["trosofortueberweisung"];
        }

        /**
         * Renders notification message for passed release.
         *
         * @param trosofortueberweisungrelease $oSOFORTRelease
         *
         * @return mixed|string|void
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected function _renderUpdateNotificationMessage($oSOFORTRelease)
        {
            $oSmarty = oxRegistry::get("oxUtilsView")->getSmarty();
            $oSmarty->assign('oView', $this);
            $oSmarty->assign('oViewConf', $this->getViewConfig());
            
            $oSmarty->assign('sTroDownloadLink', $oSOFORTRelease->getDownload());
            $oSmarty->assign('sTroAutomaticInstallationLink', 'index.php?cl=trosofortueberweisung_update&stoken=' . $this->getSession()->getSessionChallengeToken() . '&trosofortueberweisung_version=' . $oSOFORTRelease->getVersion('_'));
            $oSmarty->assign('sTroChangeLogLink', $this->getSOFORTConfig()->getChangeLogUrl());
            $oSmarty->assign('aTroNewVersion', array($oSOFORTRelease->getVersion()));

            return $oSmarty->fetch('trosofortueberweisung_updateavailable.tpl');
        }

        /**
         * Renders notification message for passed release.
         *
         * @return mixed|string|void
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected function _renderUpdateNotificationMessageLatestVersionInstalled()
        {
            $oSmarty = oxRegistry::get("oxUtilsView")->getSmarty();
            $oSmarty->assign('oView', $this);
            $oSmarty->assign('oViewConf', $this->getViewConfig());

            return $oSmarty->fetch('trosofortueberweisung_noupdateavailable.tpl');
        }

        /**
         * Renders notification message for passed release.
         *
         * @param Exception $oException
         *
         * @return mixed|string|void
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected function _renderUpdateNotificationFailedMessage($oException)
        {
            $oSmarty = oxRegistry::get("oxUtilsView")->getSmarty();
            $oSmarty->assign('oView', $this);
            $oSmarty->assign('oViewConf', $this->getViewConfig());

            return $oSmarty->fetch('trosofortueberweisung_updateavailable_failed.tpl');
        }

        /**
         * Action function that checks for module changes.
         *
         * @return string
         * @author tronet GmbH
         * @since  7.0.0
         * @version  7.0.2
         */
        public function troCheckForChanges()
        {
            $sTroCheckedChangesFailedMessage = '';
            $oSOFORTConfig = oxNew('trosofortueberweisungconfig');
            $sDownloadLinkRaw = $oSOFORTConfig->getTroMetaHashLinkRaw();
            $sDownloadFile = sprintf($sDownloadLinkRaw, $this->getCurrentVersion());

            if ($this->_troUrlExists($sDownloadFile))
            {
                /** @var trosofortueberweisungcurl $oSOFORTcURL */
                $oSOFORTcURL = oxNew('trosofortueberweisungcurlutility', $sDownloadFile);
                $oSOFORTcURL->curlSetOpt(CURLOPT_RETURNTRANSFER, 1);
                $oXML = $oSOFORTcURL->curlExec();
                $oSOFORTcURL->closeCh();

                if ($oXML)
                {
                    $oXml = new \SimpleXMLElement($oXML);
                    $sModulePath = getShopBasePath() . 'modules/';
                    $aChangedFiles = $this->getSOFORTDirectoryUtility()->getChangedFilesFromDirectory($sModulePath, $oXml);
                    $blTroCheckedChangesFailed = false;
                }
                else
                {
                    if ($oSOFORTcURL->getErrorNumber() > 0)
                    {
                        $aErrorCodes = $oSOFORTcURL->getErrorCodes();
                        $sErrorCodeName = $aErrorCodes[$oSOFORTcURL->getErrorNumber()];
                        $sLanguageKey = 'TRO_SOFORT_' . $sErrorCodeName;
                        $sTroCheckedChangesFailedMessage = oxRegistry::getLang()->translateString( $sLanguageKey );
                    }
                    $aChangedFiles = array('changedCoreFiles' => 0);
                    $blTroCheckedChangesFailed = true;
                }
            }
            else
            {
                $sTroCheckedChangesFailedMessage = oxRegistry::getLang()->translateString('TRO_SOFORT_CURLE_COULDNT_CONNECT');
                $aChangedFiles = array('changedCoreFiles' => 0);
                $blTroCheckedChangesFailed = true;
            }

            $this->addTplParam('aTroChangedFiles', $aChangedFiles);
            $this->addTplParam('blTroCheckedChanges', true);
            $this->addTplParam('blTroCheckedChangesFailed', $blTroCheckedChangesFailed);
            $this->addTplParam('sTroCheckedChangesFailedMessage', $sTroCheckedChangesFailedMessage);
        }

        /**
         * Check whether $sUrl is exists.
         *
         * @param string $sUrl Url to check
         * @return bool $blUrlExists
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected function _troUrlExists($sUrl)
        {
            $aFileHeaders = @get_headers($sUrl);
            if ($aFileHeaders[0] == 'HTTP/1.1 404 Not Found')
            {
                $blUrlExists = false;
            }
            else
            {
                $blUrlExists = true;
            }

            return $blUrlExists;
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

        /**
         * @return object|trosofortueberweisungdirectoryutility
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getSOFORTDirectoryUtility()
        {
            if ($this->_oSOFORTDirectoryUtility == null)
            {
                $this->_oSOFORTDirectoryUtility = oxNew('trosofortueberweisungdirectoryutility');
            }

            return $this->_oSOFORTDirectoryUtility;
        }

        /**
         * @param trosofortueberweisungconfig $oSOFORTConfig
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function setSOFORTConfig($oSOFORTConfig)
        {
            $this->_oSOFORTConfig = $oSOFORTConfig;
        }

        /**
         * @param $oSOFORTDirectoryUtility
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function setSOFORTDirectoryUtility($oSOFORTDirectoryUtility)
        {
            $this->_oSOFORTDirectoryUtility = $oSOFORTDirectoryUtility;
        }
    }