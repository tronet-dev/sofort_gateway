[{include file="headitem.tpl" title="CONTENT_MAIN_TITLE"|oxmultilangassign}]

<link rel="stylesheet" href="[{$oViewConf->getModuleUrl('trosofortueberweisung')}]/out/admin/src/css/trosofortueberweisung.css"/>
<link rel="stylesheet" href="[{$oViewConf->getModuleUrl('trosofortueberweisung')}]/out/admin/src/css/jquery-ui.min.css"/>
<link rel="stylesheet" href="[{$oViewConf->getModuleUrl('trosofortueberweisung')}]/out/admin/src/css/jquery-ui.structure.min.css"/>
<link rel="stylesheet" href="[{$oViewConf->getModuleUrl('trosofortueberweisung')}]/out/admin/src/css/jquery-ui.theme.min.css"/>

<div id="tro-sofort-main-page-wrap">
    <h2>
        <img src="[{$oViewConf->getBaseDir()}]modules/tronet/tronet.gif"/> [{oxmultilang ident="TRO_SOFORT_BY_TRONET"}]
    </h2>
    <p>
        [{oxmultilang ident="TRO_SOFORT_UPDATE_MAIN_INTROTEXT"}]
        <br/>
        <br/>

        [{assign var='sTroHomeUrl' value=$oViewConf->getHomeLink()}]
        [{assign var='sTroCheckNewVersion' value=$sTroHomeUrl|cat:'&cl=trosofortueberweisung_main&fnc=troCheckForUpdates'}]
        <a href="[{$sTroCheckNewVersion}]" class="tro-sofort-link-button [{if $oView->getFncName() && $oView->getFncName() == 'troCheckForUpdates'}]tro-sofort-link-button-active[{/if}]">
            [{oxmultilang ident="TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_NEW_VERSION"}]
        </a>

        [{assign var='sTroCheckForChanges' value=$sTroHomeUrl|cat:'&cl=trosofortueberweisung_main&fnc=troCheckForChanges'}]
        <a href="[{$sTroCheckForChanges}]" class="tro-sofort-link-button [{if $oView->getFncName() && $oView->getFncName() == 'troCheckForChanges'}]tro-sofort-link-button-active[{/if}]">
            [{oxmultilang ident="TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_CORE_CHANGES"}]
        </a>
    </p>

    <hr/>
    [{if $blTroCheckedForUpdates}]
        <h3>
            [{oxmultilang ident="TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_NEW_VERSION_DONE_HL"}]
        </h3>
        <div class="messagebox">
            [{oxmultilang ident="MAIN_INFO"}]:<br/>
            [{foreach from=$aTroMessage.aMessage key="sTroMessageKey" item="sTroMessageItem"}]
                <p class="[{$sTroMessageKey}]">
                    [{$sTroMessageItem}]
                </p>
            [{/foreach}]
        </div>
    [{elseif $blTroCheckedChanges}]
        <h3>
            [{oxmultilang ident="TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_CORE_CHANGES_DONE_HL"}]
        </h3>
        [{if $blTroCheckedChangesFailed}]
            <p>
                [{if $sTroCheckedChangesFailedMessage}]
                    [{$sTroCheckedChangesFailedMessage}]
                [{else}]
                    [{oxmultilang ident="TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_CORE_CHANGES_ERROR"}]
                [{/if}]
            </p>
        [{elseif $aTroChangedFiles.changedCoreFiles > 0}]
            <p>
                <u>[{oxmultilang ident="TRO_SOFORT_UPDATE_CORE_FILES_BEEN_MODIFIED_USER_HINT"}]:</u>
            </p>
            <ul>
                [{foreach from=$aTroChangedFiles key="aTroChangedFilesKey" item="aTroChangedFilesItem"}]
                    [{if $aTroChangedFilesKey != 'changedCoreFiles'}]
                        <li>[{$aTroChangedFilesItem}]</li>
                    [{/if}]
                [{/foreach}]
            </ul>
            <p>
                [{oxmultilang ident="TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_CORE_CHANGES_DONE_HINT"}]
            </p>
        [{else}]
            [{oxmultilang ident="TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_CORE_CHANGES_DONE_NO_CHANGES"}]
        [{/if}]
    [{else}]
        [{oxmultilang ident="TRO_SOFORT_UPDATE_MAIN_CHOOSE_ACTION"}]
    [{/if}]
</div>
<div class="tro-sofort-bottom-image-container">
    <a href="http://www.tronet.media" target="_blank"><img class="tro-sofort-bottom-left-1" src="[{$oViewConf->getBaseDir()}]modules/tronet/logo_tronet.media.png"/></a>
    <a href="http://www.sofort.com" target="_blank"><img class="tro-sofort-bottom-left-2" src="[{$oViewConf->getBaseDir()}]modules/tronet/trosofortueberweisung/logo_sofort.png"/></a>
</div>
                        
[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
