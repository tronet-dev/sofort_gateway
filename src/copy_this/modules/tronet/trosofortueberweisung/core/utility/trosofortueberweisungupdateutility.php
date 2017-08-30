<?php

    /**
     * Provides methods for module updating.
     *
     * @file          trosofortueberweisungupdateutility.php
     * @link          http://www.tro.net
     * @copyright (C) tronet GmbH 2017
     * @package       modules
     * @addtogroup    core/utility
     * @author        tronet GmbH
     * @since         7.0.0
     * @todo          Implement error handling
     */
    class trosofortueberweisungupdateutility
    {
        /**
         * @var oxConfig $_oConfig
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_oConfig = null;

        /**
         * @var oxModule $_oModule
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_oModule = null;

        /**
         * @var string $_sPathToOXIDTmpDirectory
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_sPathToOXIDTmpDirectory = null;

        /**
         * @var string $_sPathToSOFORTModule
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_sPathToSOFORTModule = null;

        /**
         * Creates a backup of the module tronet/trosofortueberweisung/ in the OXID tmp/* directory.
         *
         * @return string Returns the archive path+name
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function createModuleBackup()
        {
            $sDirectoryToArchive = $this->getPathToSOFORTModule();
            $sSaveArchiveInDirectory = $this->getPathToOXIDExportDirectory();
            $sArchiveName = 'trosofortueberweisung__' . date("Ymd_His", time()) . '.zip';

            $oTroSOFORTDirectoryUtility = oxNew('trosofortueberweisungdirectoryutility');
            $oTroSOFORTDirectoryUtility->createZipArchive($sSaveArchiveInDirectory . $sArchiveName, $sDirectoryToArchive);

            return $sSaveArchiveInDirectory . $sArchiveName;
        }

        /**
         * @return string
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getPathToSOFORTModule()
        {
            if ($this->_sPathToSOFORTModule == null)
            {
                $sShopHomeDir = $this->getConfig()->getConfigParam('sShopDir');
                $sPathToSOFORTModule = $sShopHomeDir . 'modules/' . $this->getModule()->getModulePath("trosofortueberweisung");
                $this->_setPathToSOFORTModule($sPathToSOFORTModule);
            }

            return $this->_sPathToSOFORTModule;
        }

        /**
         * @return oxConfig
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getConfig()
        {
            if ($this->_oConfig == null)
            {
                $this->_setConfig(oxRegistry::getConfig());
            }

            return $this->_oConfig;
        }

        /**
         * @param oxConfig $oConfig
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected function _setConfig($oConfig)
        {
            $this->_oConfig = $oConfig;
        }

        /**
         * @return oxModule
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getModule()
        {
            if ($this->_oModule == null)
            {
                $this->_setModule(oxNew("oxModule"));
            }

            return $this->_oModule;
        }

        /**
         * @param oxModule $oModule
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected function _setModule($oModule)
        {
            $this->_oModule = $oModule;
        }

        /**
         * @param string $sPathToSOFORTModule
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected function _setPathToSOFORTModule($sPathToSOFORTModule)
        {
            $this->_sPathToSOFORTModule = $sPathToSOFORTModule;
        }

        /**
         * @return string
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getPathToOXIDExportDirectory()
        {
            if ($this->_sPathToOXIDTmpDirectory == null)
            {
                $sShopHomeDirectory = $this->getConfig()->getConfigParam('sShopDir');
                $sOXIDTmpDirectory = $sShopHomeDirectory . 'export/';
                $this->_setPathToOXIDTmpDirectory($sOXIDTmpDirectory);
            }

            return $this->_sPathToOXIDTmpDirectory;
        }

        /**
         * @param string $sPathToOXIDTmpDirectory
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected function _setPathToOXIDTmpDirectory($sPathToOXIDTmpDirectory)
        {
            $this->_sPathToOXIDTmpDirectory = $sPathToOXIDTmpDirectory;
        }

        /**
         * Downloads a specific release from tronet-Server onto current server.
         *
         * @param trosofortueberweisungrelease $oSOFORTRelease
         *
         * @return string $sArchiveName
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function downloadRelease($oSOFORTRelease)
        {
            $sCopyPath = $this->getPathToOXIDTmpDirectory();

            $sTemporaryFile = $oSOFORTRelease->getArchiveName();
            $sFullPath = $sCopyPath . '/' . $sTemporaryFile;

            $blCopySuccessful = @copy($oSOFORTRelease->getDownload(), $sFullPath);
            $sArchiveName = $sFullPath;

            if ($blCopySuccessful && file_exists($sArchiveName))
            {
                $mReturn = $sArchiveName;
            }
            else
            {
                $mReturn = false;
            }

            return $mReturn;
        }

        /**
         * @return string
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getPathToOXIDTmpDirectory()
        {
            if ($this->_sPathToOXIDTmpDirectory == null)
            {
                $sShopHomeDirectory = $this->getConfig()->getConfigParam('sShopDir');
                $sOXIDTmpDirectory = $sShopHomeDirectory . 'tmp/';
                $this->_setPathToOXIDTmpDirectory($sOXIDTmpDirectory);
            }

            return $this->_sPathToOXIDTmpDirectory;
        }

        /**
         * Extracts a given archive into defined directory and optionally deletes the
         * archive afterwards.
         *
         * @param string $sArchiveName Define a valid path to the archive.
         * @param string $sExtractIntoDirectory In this directory data are extracted to.
         * @param bool   $blDeleteArchive Define whether the archive will be deleted after successful extraction.
         *
         * @return bool $blSuccess
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function extractArchive($sArchiveName, $sExtractIntoDirectory, $blDeleteArchive)
        {
            $blSuccess = false;
            $oZipArchive = new ZipArchive();
            $res = $oZipArchive->open($sArchiveName);

            if ($res === true)
            {
                $oZipArchive->extractTo($sExtractIntoDirectory);
                $oZipArchive->close();
                $blSuccess = true;
            }

            if ($blDeleteArchive)
            {
                unlink($sArchiveName);
            }

            return $blSuccess;
        }

        /**
         * Copies data from source to target directory.
         *
         * @param string $sSourceDirectory Data to be copied.
         * @param string $sTargetDirectory Directory in what data will be copied.
         *
         * @return mixed $mReturn
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function copyDirectory($sSourceDirectory, $sTargetDirectory)
        {
            if (is_dir($sSourceDirectory) && is_dir($sTargetDirectory))
            {
                $mReturn = true;

                $aSplFileInfo = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(realpath($sSourceDirectory)), RecursiveIteratorIterator::LEAVES_ONLY);

                foreach ($aSplFileInfo as $sName => $sFile)
                {
                    if (!$sFile->isdir())
                    {
                        $sFilePath = $sFile->getRealPath();
                        $sRelativePath = substr($sFilePath, strlen(realpath($sSourceDirectory)) + 1);

                        $sTargetLocation = $sTargetDirectory . $sRelativePath;
                        $sSourceLocation = $sSourceDirectory . '/' . $sRelativePath;
                        copy($sSourceLocation, $sTargetLocation);
                    }
                }
            }
            else
            {
                $mReturn = false;
            }

            return $mReturn;
        }

        /**
         * Clears the OXID tmp/* directory.
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function clearOxidTmpDirectory()
        {
            $sShopHomeDir = $this->getConfig()->getConfigParam('sShopDir');
            $sShopTmpDir = $sShopHomeDir . 'tmp';
            $this->clearTempDirectory($sShopTmpDir, true);
        }

        /**
         * @param string $sDirectory directory to clean
         * @param bool   $blRecursiveCleaning
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function clearTempDirectory($sDirectory, $blRecursiveCleaning)
        {
            $dir = opendir($sDirectory);
            while (false !== ($file = readdir($dir)))
            {
                if (($file != '.') && ($file != '..') && ($file[0] != '.'))
                {
                    $full = $sDirectory . '/' . $file;
                    if (is_dir($full))
                    {
                        $this->clearTempDirectory($full, $blRecursiveCleaning);
                    }
                    else
                    {
                        unlink($full);
                    }
                }
            }
            closedir($dir);

            $sDirectoryFormat1 = str_replace(array(
                '\\',
                '/',
            ), DIRECTORY_SEPARATOR, $sDirectory);
            $sShopPathFormat1 = str_replace(array(
                    '\\',
                    '//',
                ), DIRECTORY_SEPARATOR, getShopBasePath()) . 'tmp';

            if ($sDirectoryFormat1 != $sShopPathFormat1)
            {
                rmdir($sDirectory);
            }
        }
    }