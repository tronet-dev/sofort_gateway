<?php

    namespace Tronet\Trosofortueberweisung\Core\Utility;

    use OxidEsales\Eshop\Core\Registry;

    /**
     * Provides methods for directory actions.
     *
     * @link          http://www.tro.net
     * @copyright (c) tronet GmbH 2017
     * @author        tronet GmbH
     *
     * @since         7.0.0
     * @version       8.0.0
     */
    class DirectoryUtility
    {
        /**
         * @var LoggingUtility $_oLoggingUtility
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        protected $_oLoggingUtility = null;

        /**
         * Creates a zip archive of given directory with given name.
         *
         * @param string $sAbsolutePathToZipArchive
         * @param string $sAbsolutePathToDirectoryToArchive
         *
         * @return \ZipArchive|null $oZipArchive
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function troCreateZipArchive($sAbsolutePathToZipArchive, $sAbsolutePathToDirectoryToArchive)
        {
            $oZipArchive = null;
            if (is_dir($sAbsolutePathToDirectoryToArchive))
            {
                $oZipArchive = new \ZipArchive();
                $oZipArchive->open($sAbsolutePathToZipArchive, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                $aSplFileInfo = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(realpath($sAbsolutePathToDirectoryToArchive)), \RecursiveIteratorIterator::LEAVES_ONLY);

                if (is_array($aSplFileInfo))
                {
                    foreach ($aSplFileInfo as $oFile)
                    {
                        if (!$oFile->isdir())
                        {
                            $sFilePath = $oFile->getRealPath();
                            $sRelativePath = substr($sFilePath, strlen(realpath($sAbsolutePathToDirectoryToArchive)) + 1);
                            $oZipArchive->addFile($sFilePath, $sRelativePath);
                        }
                    }
                }

                $oZipArchive->close();
            }
            else
            {
                $oLoggingUtility = $this->getTroLoggingUtility();
                $sLogMessage = 'Could not create zip archive because "' . $sAbsolutePathToDirectoryToArchive . '" is not a directory.';
                $oLoggingUtility->troWriteToLog('INFO', $sLogMessage);
            }

            return $oZipArchive;
        }

        /**
         * @return LoggingUtility
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function getTroLoggingUtility()
        {
            if ($this->_oLoggingUtility === null)
            {
                $this->_oLoggingUtility = oxNew(LoggingUtility::class);
            }

            return $this->_oLoggingUtility;
        }

        /**
         * Perform a check on a directory for changed files.
         *
         * @param string            $sModulePath
         * @param \SimpleXMLElement $oSimpleXMLElement
         *
         * @return array $aChangedFiles
         * 
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function getTroChangedFilesFromDirectory($sModulePath, $oSimpleXMLElement)
        {
            $aChangedFiles = ['changedCoreFiles' => 0];

            $aHashNodes = $oSimpleXMLElement->hashes->children();

            $i = 0;
            foreach ($aHashNodes as $oValue)
            {
                $sTemporaryFile = str_replace([
                    '\\',
                    '/',
                ], DIRECTORY_SEPARATOR, $sModulePath . $oValue->file);

                $sFileMessage = null;

                if (file_exists($sTemporaryFile))
                {
                    $sFileHash = hash_file('md5', $sTemporaryFile);
                    // hash is not a string but an xml node, so we need parse it to a string
                    $sValueHashString = (string) $oValue->hash;
                    if ($sFileHash !== $sValueHashString)
                    {
                        $sMessageChanged = Registry::getLang()->translateString('TRO_SOFORT_UPDATE_CORE_FILES_BEEN_MODIFIED_NOTE_CHANGED');
                        $sFileMessage = '<span class="tro-sofort-update-steps-changed-core-files-left-label">' . $sMessageChanged . '</span> "' . $oValue->file . '"';
                    }
                }
                else
                {
                    $sMessageDeleted = Registry::getLang()->translateString('TRO_SOFORT_UPDATE_CORE_FILES_BEEN_MODIFIED_NOTE_DELETED');
                    $sFileMessage = '<span class="tro-sofort-update-steps-changed-core-files-left-label">' . $sMessageDeleted . '</span> "' . $oValue->file . '"';
                }

                if (is_string($sFileMessage))
                {
                    $aChangedFiles['file_' . $i] = $sFileMessage;
                    $i++;
                }
            }

            $aChangedFiles['changedCoreFiles'] = count($aChangedFiles) - 1;

            return $aChangedFiles;
        }

        /**
         * @param LoggingUtility $oLoggingUtility
         *
         * @author  tronet GmbH
         * @since   7.0.0
         * @version 8.0.0
         */
        public function setTroLoggingUtility($oLoggingUtility)
        {
            $this->_oLoggingUtility = $oLoggingUtility;
        }
    }
