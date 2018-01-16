[{$smarty.block.parent}]
<!-- Sofortueberweisung Logo Start -->
<div>
    <a href="https://www.sofort.com/" target="_blank">
        [{if $oViewConf->getActLanguageAbbr() == 'de'}]
            <img src="https://cdn.klarna.com/1.0/shared/image/generic/logo/de_de/basic/logo_black.png" alt="[{oxmultilang ident="TRO_SOFORT_BANNER_IMG_TITLE"}]" title="[{oxmultilang ident="TRO_SOFORT_BANNER_IMG_TITLE"}]" style="max-width:150px">
        [{else}]
            <img src="https://cdn.klarna.com/1.0/shared/image/generic/logo/de_de/basic/logo_black.png" alt="[{oxmultilang ident="TRO_SOFORT_BANNER_IMG_TITLE"}]" title="[{oxmultilang ident="TRO_SOFORT_BANNER_IMG_TITLE"}]" style="max-width:150px">
        [{/if}]
    </a>
</div>
<!-- Sofortueberweisung Logo End -->
