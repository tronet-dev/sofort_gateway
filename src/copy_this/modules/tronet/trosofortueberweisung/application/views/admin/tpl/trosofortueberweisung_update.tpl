[{include file="headitem.tpl" title="tronet GmbH"}]

[{block name="admin_headitem_inccss"}]
    [{$Smarty.block.parent}]
    <link rel="stylesheet" href="[{$sTroShopMainUrl}]modules/tronet/trosofortueberweisung/out/admin/src/css/trosofortueberweisung.css"/>
    <link rel="stylesheet" href="[{$sTroShopMainUrl}]modules/tronet/trosofortueberweisung/out/admin/src/css/jquery-ui.min.css"/>
    <link rel="stylesheet" href="[{$sTroShopMainUrl}]modules/tronet/trosofortueberweisung/out/admin/src/css/jquery-ui.structure.min.css"/>
    <link rel="stylesheet" href="[{$sTroShopMainUrl}]modules/tronet/trosofortueberweisung/out/admin/src/css/jquery-ui.theme.min.css"/>
[{/block}]

[{block name="admin_headitem_incjs"}]
    [{$Smarty.block.parent}]
    <script src="[{$sTroShopMainUrl}]modules/tronet/trosofortueberweisung/out/admin/src/js/jquery-3.0.0.min.js"></script>
    <script src="[{$sTroShopMainUrl}]modules/tronet/trosofortueberweisung/out/admin/src/js/jquery-ui.min.js"></script>
    <script src="[{$sTroShopMainUrl}]modules/tronet/trosofortueberweisung/out/admin/src/js/widgets/troSOFORTUpdateWidget.js"></script>
[{/block}]

<div id="tro-sofort-main-page-wrap">
<h2>
    <img src="[{$sTroShopMainUrl}]modules/tronet/tronet.gif"/> [{oxmultilang ident="TRO_SOFORT_BY_TRONET"}]
</h2>

<p>
    [{oxmultilang ident="TRO_SOFORT_UPDATE_VERSION_IS_INSTALLED" args=$aTroNewVersion}]
    <br/>
    <br/><b>[{oxmultilang ident="TRO_SOFORT_UPDATE_IMPORTANT_NOTE_LABEL"}]</b> [{oxmultilang ident="TRO_SOFORT_UPDATE_IMPORTANT_NOTE_DESCRIPTION"}]
</p>

<h3>[{oxmultilang ident="TRO_SOFORT_UPDATE_STEPS_TITLE"}]:</h3>
<ul id="tro-sofort-update-steps" class="tro-sofort-update-steps">
    <li class="tro-todo"
        data-tro-action="troChangedModuleCoreFiles" data-tro-prompt-on-failure="1">[{oxmultilang ident="TRO_SOFORT_UPDATE_HAS_CORE_FILES_BEEN_MODIFIED"}]</li>
    <li class="tro-todo"
        data-tro-action="troDownloadLatestModuleRelease" data-tro-prompt-on-failure="0">[{oxmultilang ident="TRO_SOFORT_UPDATE_DOWNLOAD_RELEASE_INTO_TMP_DIR"}]</li>
    <li class="tro-todo"
        data-tro-action="troExtractLatestModuleRelease" data-tro-prompt-on-failure="0">[{oxmultilang ident="TRO_SOFORT_UPDATE_EXTRACT_DOWNLOADED_ARCHIVE"}]</li>
    <li class="tro-todo"
        data-tro-action="troCreateModuleBackup" data-tro-prompt-on-failure="1">[{oxmultilang ident="TRO_SOFORT_UPDATE_CREATE_BACKUP_OF_CURRENT_VERSION"}]</li>
    <li class="tro-todo"
        data-tro-action="troPerformUpdate" data-tro-prompt-on-failure="0">[{oxmultilang ident="TRO_SOFORT_UPDATE_PERFORM_UPDATE"}]</li>
    <li class="tro-todo"
        data-tro-action="troRefreshModule" data-tro-prompt-on-failure="1">[{oxmultilang ident="TRO_SOFORT_UPDATE_REACTIVATE_MODULE"}]</li>
    <li class="tro-todo"
        data-tro-action="troClearOxidTmpDirectory" data-tro-prompt-on-failure="1">[{oxmultilang ident="TRO_SOFORT_UPDATE_CLEAR_TMP_DIR"}]</li>
</ul>

<div class="tro-sofort-update-successfullyupdated"
     style="display: none;">[{oxmultilang ident="TRO_SOFORT_UPDATE_SUCCESSFUL_USERNOTE" args=$aTroNewVersion}]</div>
<div class="tro-sofort-update-failedtoupdated"
     style="display: none;">[{oxmultilang ident="TRO_SOFORT_UPDATE_LEGEND_FAILED_USERNOTE" args=$aTroNewVersion}]</div>

<fieldset class="tro-sofort-update-fieldset">
    <legend>[{oxmultilang ident="TRO_SOFORT_UPDATE_LEGEND_TITLE"}]:</legend>
    <ul class="tro-sofort-update-fieldset-list">
        <li class="tro-todo">
            <span class="tro-sofort-update-legend-todo"></span> [{oxmultilang ident="TRO_SOFORT_UPDATE_LEGEND_TODO"}]
        </li>
        <li class="tro-successful">
            <span class="tro-sofort-update-legend-successful"></span> [{oxmultilang ident="TRO_SOFORT_UPDATE_LEGEND_SUCCESSFUL"}]
        </li>
        <li class="tro-failed">
            <span class="tro-sofort-update-legend-failed"></span> [{oxmultilang ident="TRO_SOFORT_UPDATE_LEGEND_FAILED"}]
        </li>
        <li class="tro-failed-but-continued-by-users-request">
            <span class="tro-sofort-update-legend-failed-but-continued-by-users-request"></span> [{oxmultilang ident="TRO_SOFORT_UPDATE_LEGEND_FAILED_BUT_CONTINUED"}]
        </li>
    </ul>
</fieldset>

[{capture assign="troSOFORTUpdateWidget_Call"}]
    $('body').troSOFORTUpdateWidget({
        actionListId: 'tro-sofort-update-steps',
        shopMainUrl: '[{$sTroShopMainUrl}]',
        securityToken: '[{$sTroSessionToken}]',
        moduleVersion: '[{$sTroNewVersionRaw}]',
        changedCoreFileQuestion: '[{oxmultilang ident="TRO_SOFORT_UPDATE_HAS_CORE_FILES_BEEN_MODIFIED_USER_QUESTION"}]',
        changedCoreFileHint: '[{oxmultilang ident="TRO_SOFORT_UPDATE_CORE_FILES_BEEN_MODIFIED_USER_HINT"}]',
		couldNotCreateBackupQuestion: '[{oxmultilang ident="TRO_SOFORT_UPDATE_CREATE_BACKUP_OF_CURRENT_VERSION_QUESTION"}]',
		couldNotReactivateModule: '[{oxmultilang ident="TRO_SOFORT_UPDATE_REACTIVATE_MODULE_QUESTION"}]',
		couldNotClearOxidTmpDir: '[{oxmultilang ident="TRO_SOFORT_UPDATE_REACTIVATE_MODULE_QUESTION"}]',
        errorHintDefault: '[{oxmultilang ident="TRO_SOFORT_UPDATE_ERROR_HINT_DEFAULT"}]'
    });
[{/capture}]

[{oxscript add=$troSOFORTUpdateWidget_Call}]


</div>
<div class="tro-sofort-bottom-image-container">
    <img class="tro-sofort-bottom-left-1" src="[{$sTroShopMainUrl}]modules/tronet/logo_tronet.media.png"/>
    <img class="tro-sofort-bottom-left-2" src="[{$sTroShopMainUrl}]modules/tronet/trosofortueberweisung/logo_sofort.png"/>
</div>

[{include file="bottomitem.tpl"}]