[{$smarty.block.parent}]
<!-- Sofortueberweisung Logo Start -->
<div>
    <a href="https://www.sofort.com/" target="_blank">
        [{if $oViewConf->getActLanguageAbbr() == 'de'}]
        <img src="https://images.sofort.com/de/su/SOFORT_banner_de_150x200.png" alt="[{oxmultilang ident="TRO_SOFORT_BANNER_IMG_TITLE"}]" title="[{oxmultilang ident="TRO_SOFORT_BANNER_IMG_TITLE"}]" height="200" width="150">
        [{else}]
        <img src="https://images.sofort.com/uk/sb/SOFORT_banner_uk_120x180_tuev.png" alt="[{oxmultilang ident="TRO_SOFORT_BANNER_IMG_TITLE"}]" title="[{oxmultilang ident="TRO_SOFORT_BANNER_IMG_TITLE"}]" height="180" width="120">
        [{/if}]
    </a>
</div>
<!-- Sofortueberweisung Logo End -->
