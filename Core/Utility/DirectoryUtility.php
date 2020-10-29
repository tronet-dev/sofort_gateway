<?php

    namespace Tronet\Trosofortueberweisung\Core\Utility;

    use OxidEsales\Eshop\Core\Registry;

    /**
     * Provides methods for directory actions.
     *
     * @link          http://www.tro.net
     * @copyright (c) tronet GmbH 2018
     * @author        tronet GmbH
     *
     * @since         7.0.0
     * @version       8.0.9
     */
    class DirectoryUtility
    {
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
    }
