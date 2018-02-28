<tr>
    <td class="edittext">[{oxmultilang ident="ORDER_OVERVIEW_PAYMENTTYPE"}]:</td>
    <td class="edittext">
        <b>[{$edit->getTroPaymentName()}]</b>[{if $edit->getTroPaymentStatus()}] ([{$edit->getTroPaymentStatus()}])[{/if}]
    </td>
</tr>
<tr>
    <td class="edittext">[{oxmultilang ident="ORDER_OVERVIEW_DELTYPE"}]:</td>
    <td class="edittext"><b>[{$deliveryType->oxdeliveryset__oxtitle->value}]</b><br></td>
</tr>
