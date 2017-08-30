<?php
    /**
     * English language for module tronet/trosofortueberweisung.
     *
     * @file          trosofortueberweisung_lang.php
     * @link          http://www.tro.net
     * @copyright (C) tronet GmbH 2017
     * @package       modules
     * @addtogroup    modules
     */
    $sLangName = "English";
    $iLangNr = 1;

    $sTroGatewayReasonTemplate = '<p>Specify the transaction\'s reason below.</p>                  
					  <p>The following placeholder are available:</p>
					  <p>
						 <table  cellspacing="0" cellpadding="0" border="0" height="100%" width="50%">
							<tr>
							   <td class="edittext">Order-no:</td>
							   <td class="edittext">[BSTNR]</td>
							</tr>
							<tr>
							   <td class="edittext">Customer-no:</td>
							   <td class="edittext">[KNR]</td>
							</tr>
							<tr>
							   <td class="edittext">Customer name:</td>
							   <td class="edittext">[KNAME]</td>
							</tr>
							<tr>
							   <td class="edittext">Order-date:</td class="edittext">
							   <td class="edittext">[DATUM]</td>
							</tr>
							<tr>
							   <td class="edittext">Total price:</td class="edittext">
							   <td class="edittext">[PRICE]</td>
							</tr>
							<tr>
							   <td class="edittext">Shopname:</td class="edittext">
							   <td class="edittext">[SHP]</td>
							</tr>
						 </table>
					  <p>
					  <p> 
					  Caution: The maximum length after the replacement may not exceed 27 characters!</p>
      ';

    $aLang = array(
        'charset'                                  => 'ISO-8859-15',

        // module settings
        'SHOP_MODULE_GROUP_main'                   => 'General settings',
        'SHOP_MODULE_GROUP_troPaymentHandling'     => 'Payment handling',
        'SHOP_MODULE_GROUP_troUpdateRoutine'       => 'Updating',
        'SHOP_MODULE_GROUP_troOtherSettings'       => 'Other',
        'SHOP_MODULE_iTroGatewayCanceledOrders'    => 'Cancel or delete aborted orders?',
        'SHOP_MODULE_iTroGatewayCanceledOrders_0'  => 'Cancel',
        'SHOP_MODULE_iTroGatewayCanceledOrders_1'  => 'Delete',
        'SHOP_MODULE_sTroGatewayConfKey'           => 'Configuration-key',
        'HELP_SHOP_MODULE_sTroGatewayConfKey'      => 'You find the configuration-key in your project settings at the SOFORT backend .',
        'SHOP_MODULE_sTroGatewayVersion'           => 'Module-version',
        'SHOP_MODULE_blTroGateWayUpdateCheck'      => 'Automatically check for updates?',
        'HELP_SHOP_MODULE_blTroGateWayUpdateCheck' => 'When this option is selected a message is shown on the startpage
														    as there is a new module version for your OXID version.',

        'SHOP_MODULE_sTroGatewayReason'                            => $sTroGatewayReasonTemplate,
        'SHOP_MODULE_sTroGatewayReason2'                           => $sTroGatewayReasonTemplate,
        'SHOP_MODULE_blTroGateWayShowLogoInDeliverAndPayment'      => 'Show SOFORT-logo in order step 3 "Pay".',
        'HELP_SHOP_MODULE_blTroGateWayShowLogoInDeliverAndPayment' => 'In case the logo does not fit visually in your theme, you may deactivate it.',

        // general interface
        'TRO_NEW_VERSION_AVAILABLE'                                => 'A new version of SOFORT BANKING is available',
        'TRO_NEW_VERSION_ALREADY_INSTALLED'                        => 'You have already installed the latest module release.',
        'TRO_NEW_VERSION_AVAILABLE_FAILED'                         => 'An error occured',
        'TRO_NEW_VERSION_AVAILABLE_FAILED_MESSAGE'                 => 'Latest SOFORT Banking-Module-Releases could not be fetched.',
        'TRO_VERSION_DOWNLOAD'                                     => 'Download new Version (%1$s)',
        'TRO_VERSION_INSTALL'                                      => 'Install new Version (%1$s)',
        'TRO_UPDATE_SUCCESSFUL'                                    => 'Modul updated successfully',
        'TRO_SOFORT_BY_TRONET'                                     => 'Online bank transfer. by tronet GmbH',
        'TRO_CHANGE_LOG_URL_DESCRIPTION'                           => 'Open the change log',

        // general interface :: update routine
        'TRO_SOFORT_UPDATE_AUTO_UPDATES_DISABLED'                  => 'No updates are processed as automatic updates are disabled in the module settings.',

        'TRO_SOFORT_UPDATE_VERSION_IS_INSTALLED'       => 'The new module version %1$s is now installed. During the
                                                            update routine below stated steps were processed.
                                                            Once the update is finished the result is displayed between
                                                            the update steps and the legend.',
        'TRO_SOFORT_UPDATE_IMPORTANT_NOTE_LABEL'       => 'Important note:',
        'TRO_SOFORT_UPDATE_IMPORTANT_NOTE_DESCRIPTION' => 'During the update process you must not clear the OXID tmp/* directory!',

        'TRO_SOFORT_UPDATE_STEPS_TITLE'                                => 'Update steps',
        'TRO_SOFORT_UPDATE_ERROR_HINT_DEFAULT'                         => 'Following issue(s) occured.',
        'TRO_SOFORT_UPDATE_HAS_CORE_FILES_BEEN_MODIFIED'               => 'Determine whether module-core-files has been adjusted.',
        'TRO_SOFORT_UPDATE_HAS_CORE_FILES_BEEN_MODIFIED_USER_QUESTION' => 'Warning: It has been detected that one or more files of the current module version have been adjusted. If you install the new version, changes would be overridden.\n\nDo you want to install the new module version anyway?',
        'TRO_SOFORT_UPDATE_CORE_FILES_BEEN_MODIFIED_USER_HINT'         => 'Following files are different to the shipped files.',
        'TRO_SOFORT_UPDATE_CORE_FILES_BEEN_MODIFIED_NOTE_CHANGED'      => 'File changed:',
        'TRO_SOFORT_UPDATE_CORE_FILES_BEEN_MODIFIED_NOTE_DELETED'      => 'File removed:',
        'TRO_SOFORT_UPDATE_DOWNLOAD_RELEASE_INTO_TMP_DIR'              => 'Download the new version into OXID tmp/* directory',
        'TRO_SOFORT_UPDATE_EXTRACT_DOWNLOADED_ARCHIVE'                 => 'Extract the downloaded archive into OXID tmp/* directory',
        'TRO_SOFORT_UPDATE_CREATE_BACKUP_OF_CURRENT_VERSION'           => 'Create a backup of the current module in OXID export/* directory',
        'TRO_SOFORT_UPDATE_CREATE_BACKUP_OF_CURRENT_VERSION_QUESTION'  => 'Warning: Could not create backup of current module.\n\nInstall new module?',
        'TRO_SOFORT_UPDATE_PERFORM_UPDATE'                             => 'Perform the update',
        'TRO_SOFORT_UPDATE_REACTIVATE_MODULE'                          => 'Reactivate the module, to apply changes in the metadata file',
        'TRO_SOFORT_UPDATE_REACTIVATE_MODULE_QUESTION'                 => 'Information: Could not reactivate module. You have to perform this step manually.',
        'TRO_SOFORT_UPDATE_CLEAR_TMP_DIR'                              => 'Clear OXID tmp/* directory',
        'TRO_SOFORT_UPDATE_CLEAR_TMP_DIR_QUESTION'                     => 'Information: Could not clear OXID tmp/* directory. It\'s recommend to do this manually.',

        'TRO_SOFORT_UPDATE_SUCCESSFUL_USERNOTE'    => 'The new module version <b>%1$s</b> has been installed!',
        'TRO_SOFORT_UPDATE_LEGEND_FAILED_USERNOTE' => 'The new module version <b>%1$s</b> could not be installed!',

        'TRO_SOFORT_UPDATE_LEGEND_TITLE'                                => 'Update steps legend',
        'TRO_SOFORT_UPDATE_LEGEND_TODO'                                 => 'This step has not been performed yet.',
        'TRO_SOFORT_UPDATE_LEGEND_SUCCESSFUL'                           => 'This step has been performed successfully.',
        'TRO_SOFORT_UPDATE_LEGEND_FAILED'                               => 'An error occured. The update process has been canceled.',
        'TRO_SOFORT_UPDATE_LEGEND_FAILED_BUT_CONTINUED'                 => 'An error occured. Due to request by user the update has been continued.',

        // general interface :: update routine :: json messages
        'TRO_SOFORT_UPDATE_JSON_RETURN_fileNotDownloaded'               => 'Could not download the file.',
        'TRO_SOFORT_UPDATE_JSON_RETURN_releaseNotFound'                 => 'The release with version %1$s could not be found.',
        'TRO_SOFORT_UPDATE_JSON_RETURN_unknownResult'                   => 'An unknown error occured.',
        'TRO_SOFORT_UPDATE_JSON_RETURN_copyFailed'                      => 'Data could not be copied from OXID tmp/* directory.',
        'TRO_SOFORT_UPDATE_JSON_RETURN_couldNotFetchXml'                => 'XML file containing hash files could not be loaded.',

        // general interface :: update routine (manual)
        'TRO_SOFORT_UPDATE_MAIN_INTROTEXT'                              => 'Check manually for both, new versions and module-core-file changes.',
        'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_NEW_VERSION'                  => 'check for new releases now',
        'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_CORE_CHANGES'                 => 'check whether module-core-files have been changed',
        'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_NEW_VERSION_DONE_HL'          => 'It has been checked for new releases',
        'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_CORE_CHANGES_DONE_HL'         => 'It has been checked for changed module-core-files',
        'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_CORE_CHANGES_ERROR'           => 'An error occured.',
        'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_CORE_CHANGES_DONE_HINT'       => '<u>Hint:</u> It is recommended to extend classes by OXID developers guidelines. If you perform an update your changes would be discarded.',
        'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_CORE_CHANGES_DONE_NO_CHANGES' => 'No module-core-files have been changed.',
        'TRO_SOFORT_UPDATE_MAIN_CHOOSE_ACTION'                          => 'Choose an action.',

        // OXID eShop navigation
        'mxtronotifications'                                            => 'Sofortgateway-Log',
        'TRONET_MENU_HEAD'                                              => 'tronet',
        'mxtrosofortueberweisung'                                       => '<img height="10px" style="margin-top: 3px;"  src="../modules/tronet/tronet.gif" alt="[tronet]"/> Pay now.',
        'mxtrosofortueberweisung_dashboard'                             => 'Dashboard',
        'mxtrosofortueberweisung_main'                                  => 'Main',

        'TRO_CURRENT_LANGUAGE' => 'en',
    );
