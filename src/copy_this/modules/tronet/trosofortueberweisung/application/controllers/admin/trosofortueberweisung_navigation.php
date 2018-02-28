<?php

    /**
     * Backend Navigation controller. Mainly used to check for updates for new SOFORT packages.
     *
     * @file          trosofortueberweisung_navigation.php
     * @link          http://www.tro.net
     * @copyright (C) tronet GmbH 2018
     * @package       modules
     * @addtogroup    application/controllers/admin
     * @author        tronet GmbH
     * @since         7.0.0
     */
    class trosofortueberweisung_navigation extends trosofortueberweisung_navigation_parent
    {
        /**
         * @var trosofortueberweisungconfig $_oSOFORTConfig
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_oSOFORTConfig = null;

        /**
         * Adds a simple check for the newest version of SOFORT for the used PHP-version.
         *
         * @return string
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function render()
        {
            $sReturn = parent::render();
            $sItem = $this->getConfig()->getRequestParameter('item');
            $sItem = ($sItem ? basename($sItem) : false);

            if (!$this->getConfig()->getRequestParameter("navReload") && $sItem == 'home.tpl')
            {
                $this->_checkSOFORTUpdates();
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
         * @author tronet GmbH
         * @since  7.0.0
         */
        private function _checkSOFORTUpdates()
        {
            if ($this->getConfig()->getConfigParam('blTroGateWayUpdateCheck'))
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
                        $this->_aViewData['aMessage']['trosofortueberweisung_update_notification'] = $this->_renderUpdateNotificationMessage($oSOFORTRelease);
                    }
                }
                catch (Exception $oException)
                {
                    // silently ignore as no error messages shall be displayed on the OXID eShop dashboard.
                }
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
         * @param trosofortueberweisungconfig $oSOFORTConfig
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function setSOFORTConfig($oSOFORTConfig)
        {
            $this->_oSOFORTConfig = $oSOFORTConfig;
        }
    }
    