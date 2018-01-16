[{*oxscript include="js/widgets/oxagbcheck.js" priority=10 *}]
[{if $sPaymentID == "trosofortgateway_su"}]
    <style type="text/css">
        #payment_form_sofortueberweisung ul {
            list-style-type: none !important;
        }
    </style>
    <div id="paymentOption_[{$sPaymentID}]"
         class="payment-option [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]active-payment[{/if}]">
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]"
                [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]
                    checked="checked"
                [{/if}]/>
        <div class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
            <div class="desc">
                [{if $oViewConf->getActLanguageAbbr() == 'de'}]
                    <a href="https://www.sofort.com/" target="_blank">
                        <img src="https://cdn.klarna.com/1.0/shared/image/generic/badge/xx_XX/pay_now/standard/pink.svg" alt="[{oxmultilang ident="TRO_SOFORT_BANNER_IMG_TITLE"}]" title="[{oxmultilang ident="TRO_SOFORT_BANNER_IMG_TITLE"}]" height="75" width="100">
                    </a>
                [{else}]
                    <a href="https://www.sofort.com/" target="_blank">
                        <img src="https://cdn.klarna.com/1.0/shared/image/generic/badge/xx_XX/pay_now/standard/pink.svg" alt="[{oxmultilang ident="TRO_SOFORT_BANNER_IMG_TITLE"}]" title="[{oxmultilang ident="TRO_SOFORT_BANNER_IMG_TITLE"}]" height="75" width="100">
                    </a>
                [{/if}]
                [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
            </div>
        </div>
    </div>
[{else}]
    [{$smarty.block.parent}]
[{/if}]