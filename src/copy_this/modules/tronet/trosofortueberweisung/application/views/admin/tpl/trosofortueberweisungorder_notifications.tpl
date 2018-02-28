[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="user_remark">
</form>

<form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="editval[oxuser__oxid]" value="[{$oxid}]">
    <input type="hidden" name="log_oxid" value="[{$oView->getLogOxid()}]">

    <table cellspacing="0" cellpadding="0" border="0" width="98%">
        <tr>
            <td valign="top" class="edittext">
                <select name="log_oxid" size="17" class="editinput" style="width:180px;" onChange="Javascript:document.myedit.submit();" [{$readonly}]>
                    [{assign var="alllogs" value=$oView->getAllLogs()}]
                    [{if $alllogs}]
                        [{foreach from=$alllogs item=allitem}]
                            <option value="[{$allitem->trogatewaylog__oxid->value}]" [{if $allitem->getId()==$oView->getLogOxid()}]SELECTED[{/if}]>
                                [{$allitem->trogatewaylog__timestamp|oxformdate:"datetime"}] [{$allitem->trogatewaylog__status}]
                            </option>
                        [{/foreach}]
                    [{/if}]
                </select>
            </td>
            <!-- Anfang rechte Seite -->
            <td valign="top" class="edittext" align="left">                
                <textarea class="editinput" cols="100" rows="17" wrap="VIRTUAL" name="logtext" readonly>[{$oView->getFLogData()}]</textarea><br>
            </td>
            <!-- Ende rechte Seite -->
        </tr>
    </table>

[{include file="bottomitem.tpl"}]
