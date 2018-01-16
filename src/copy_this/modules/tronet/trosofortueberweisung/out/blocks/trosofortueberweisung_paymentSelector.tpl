[{*oxscript include="js/widgets/oxagbcheck.js" priority=10 *}]
[{if $sPaymentID == "trosofortgateway_su"}]
    <dl>
        <dt>
            <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]"
                [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
            <label for="payment_[{$sPaymentID}]"><b>[{$paymentmethod->oxpayments__oxdesc->value}] [{if $paymentmethod->fAddPaymentSum}]([{$paymentmethod->fAddPaymentSum}] [{$currency->sign}])[{/if}]</b></label>
        </dt>
        <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
            <div class="desc">
                [{assign var="oTroConfig" value=$oView->getConfig()}]
                [{assign var="blTroGateWayShowLogoInDeliverAndPayment" value=$oTroConfig->getConfigParam('blTroGateWayShowLogoInDeliverAndPayment')}]

                [{if $blTroGateWayShowLogoInDeliverAndPayment}]
                    [{if $oViewConf->getActLanguageAbbr() == 'de'}]
                        <a href="https://www.sofort.com/" target="_blank">
                            <img src="https://cdn.klarna.com/1.0/shared/image/generic/badge/xx_XX/pay_now/standard/pink.svg" alt="[{oxmultilang ident="TRO_SOFORT_BANNER_IMG_TITLE"}]" title="[{oxmultilang ident="TRO_SOFORT_BANNER_IMG_TITLE"}]" height="75" width="100">
                        </a>
                    [{else}]
                        <a href="https://www.sofort.com/" target="_blank">
                            <img src="https://cdn.klarna.com/1.0/shared/image/generic/badge/xx_XX/pay_now/standard/pink.svg" alt="[{oxmultilang ident="TRO_SOFORT_BANNER_IMG_TITLE"}]" title="[{oxmultilang ident="TRO_SOFORT_BANNER_IMG_TITLE"}]" height="75" width="100">
                        </a>
                    [{/if}]
                [{/if}]
                [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
            </div>
        </dd>
    </dl>
[{else}]
    [{$smarty.block.parent}]
[{/if}]