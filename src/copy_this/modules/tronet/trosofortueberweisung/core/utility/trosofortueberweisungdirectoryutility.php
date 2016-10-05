<?php

    /**
     * Provides methods for directory actions.
     *
     * @file          trosofortueberweisungupdateutility.php
     * @link          http://www.tro.net
     * @copyright (C) tronet GmbH 2013
     * @package       modules
     * @addtogroup    core/utility
     * @author        tronet GmbH
     * @since         7.0.0
     */
    class trosofortueberweisungdirectoryutility
    {
        /**
         * @var trosofortueberweisungloggingutility $_oTroLoggingUtility
         * @author tronet GmbH
         * @since  7.0.0
         */
        protected $_oTroLoggingUtility = null;

        /**
         * Creates a zip archive of given directory with given name.
         *
         * @param $sAbsolutePathToZipArchive
         * @param $sAbsolutePathToDirectoryToArchive
         *
         * @return ZipArchive|null $oZipArchive
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function createZipArchive($sAbsolutePathToZipArchive, $sAbsolutePathToDirectoryToArchive)
        {
            $oZipArchive = null;
            if (is_dir($sAbsolutePathToDirectoryToArchive))
            {
                $oZipArchive = new ZipArchive();
                $oZipArchive->open($sAbsolutePathToZipArchive, ZipArchive::CREATE | ZipArchive::OVERWRITE);

                $aSplFileInfo = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(realpath($sAbsolutePathToDirectoryToArchive)), RecursiveIteratorIterator::LEAVES_ONLY);

                foreach ($aSplFileInfo as $sName => $sFile)
                {
                    if (!$sFile->isdir())
                    {
                        $sFilePath = $sFile->getRealPath();
                        $sRelativePath = substr($sFilePath, strlen(realpath($sAbsolutePathToDirectoryToArchive)) + 1);
                        $oZipArchive->addFile($sFilePath, $sRelativePath);
                    }
                }

                $oZipArchive->close();
            }
            else
            {
                $oLogUtility = $this->getTroLoggingUtility();
                $LogMessage = 'Could not create zip archive because "' . $sAbsolutePathToDirectoryToArchive . '" is not a directory.';
                $oLogUtility->writeToLog('INFO', $LogMessage);
            }

            return $oZipArchive;
        }

        /**
         * @return trosofortueberweisungloggingutility
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getTroLoggingUtility()
        {
            if ($this->_oTroLoggingUtility == null)
            {
                $this->_oTroLoggingUtility = oxNew('trosofortueberweisungloggingutility');
            }

            return $this->_oTroLoggingUtility;
        }

        /**
         * Perform a check on a directory for changed files.
         *
         * @param string           $sLookUpDirectory
         * @param SimpleXMLElement $oXml
         *
         * @return array $aChangedFiles
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function getChangedFilesFromDirectory($sLookUpDirectory, $oXml)
        {
            $aChangedFiles = array('changedCoreFiles' => 0);

            $aHashNodes = $oXml->hashes->children();
            $sModulePath = $sLookUpDirectory;

            $i = 0;
            foreach ($aHashNodes as $key => $value)
            {
                $sTempFile = str_replace(array(
                    '\\',
                    '/',
                ), DIRECTORY_SEPARATOR, $sModulePath . $value->file);

                $sFileMessage = null;

                if (file_exists($sTempFile))
                {
                    $sFileHash = hash_file('md5', $sTempFile);

                    if ($sFileHash != $value->hash)
                    {
                        $sMessageChanged = oxRegistry::getLang()->translateString('TRO_SOFORT_UPDATE_CORE_FILES_BEEN_MODIFIED_NOTE_CHANGED');
                        $sFileMessage = '<span class="tro-sofort-update-steps-changed-core-files-left-label">' . $sMessageChanged . '</span> "' . $value->file . '"';
                    }
                }
                else
                {
                    $sMessageDeleted = oxRegistry::getLang()->translateString('TRO_SOFORT_UPDATE_CORE_FILES_BEEN_MODIFIED_NOTE_DELETED');
                    $sFileMessage = '<span class="tro-sofort-update-steps-changed-core-files-left-label">' . $sMessageDeleted . '</span> "' . $value->file . '"';
                }

                if ($sFileMessage != null)
                {
                    $aChangedFiles['file_' . $i] = $sFileMessage;
                    $i++;
                }
            }
            $aChangedFiles['changedCoreFiles'] = count($aChangedFiles) - 1;

            return $aChangedFiles;
        }

        /**
         * @param trosofortueberweisungloggingutility $oTroLoggingUtility
         *
         * @author tronet GmbH
         * @since  7.0.0
         */
        public function setTroLoggingUtility($oTroLoggingUtility)
        {
            $this->_oTroLoggingUtility = $oTroLoggingUtility;
        }


    }