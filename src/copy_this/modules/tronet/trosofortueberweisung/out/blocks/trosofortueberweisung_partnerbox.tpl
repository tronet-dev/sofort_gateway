[{$smarty.block.parent}]
<!-- Sofortueberweisung Logo Start -->
<div>
    <a href="https://www.sofort.com/" target="_blank">
        [{if $oViewConf->getActLanguageAbbr() == 'de'}]
        <img src="https://cdn.klarna.com/1.0/shared/image/generic/badge/de_de/pay_now/standard/pink.svg" alt="[{oxmultilang ident="TRO_SOFORT_BANNER_IMG_TITLE"}]" title="[{oxmultilang ident="TRO_SOFORT_BANNER_IMG_TITLE"}]" height="200" width="150">
        [{else}]
        <img src="https://cdn.klarna.com/1.0/shared/image/generic/badge/en_gb/pay_now/descriptive/pink.svg" alt="[{oxmultilang ident="TRO_SOFORT_BANNER_IMG_TITLE"}]" title="[{oxmultilang ident="TRO_SOFORT_BANNER_IMG_TITLE"}]" height="180" width="120">
        [{/if}]
    </a>
</div>
<!-- Sofortueberweisung Logo End -->
