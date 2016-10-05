<tr>
    <td class="edittext">[{oxmultilang ident="ORDER_OVERVIEW_PAYMENTTYPE"}]:</td>
    <td class="edittext">
        <b>[{$paymentType->oxpayments__oxdesc->value}]</b>[{if $oView->troGetPaymentStatus()}] ([{$oView->troGetPaymentStatus()}])[{/if}]
    </td>
</tr>
<tr>
    <td class="edittext">[{oxmultilang ident="ORDER_OVERVIEW_DELTYPE"}]:</td>
    <td class="edittext"><b>[{$deliveryType->oxdeliveryset__oxtitle->value}]</b><br></td>
</tr>
