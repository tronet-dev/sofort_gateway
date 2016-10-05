[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<script type="text/javascript">
<!--
function EditThis( sID)
{
    var oTransfer = parent.edit.document.getElementById("transfer");
    oTransfer.oxid.value = sID;
    oTransfer.cl.value='[{if $actlocation}][{$actlocation}][{else}][{ $default_edit }][{/if}]';
    oTransfer.fnc.value = 'chshp';

    //forcing edit frame to reload after submit
    top.forceReloadingEditFrame();

    var oSearch = document.getElementById("search");
    oSearch.oxid.value = sID;

    oSearch.submit();
}

function DeleteThis( sID)
{
    var currentshop = [{$oxid}];
    var newshop = (sID == currentshop)?1:currentshop;

    blCheck = confirm("[{ oxmultilang ident="SHOP_LIST_YOUWANTTODELETE" }]");
    if( blCheck == true)
    {   var oSearch = document.getElementById("search");
        oSearch.oxid.value=sID;
        oSearch.fnc.value='Deleteentry';
        oSearch.actedit.value=0;
        oSearch.submit();

        var oTransfer = parent.edit.document.getElementById("transfer");
        oTransfer.oxid.value = newshop;
        oTransfer.actshop.value = newshop;
        oTransfer.cl.value='[{ $default_edit }]';

        //forcing edit frame to reload after submit
        top.forceReloadingEditFrame();
    }
}
function ChangeEditBar( sLocation, sPos)
{
    var oSearch = document.getElementById("search");
    oSearch.actedit.value=sPos;
    oSearch.submit();

    var oTransfer = parent.edit.document.getElementById("transfer");
    oTransfer.cl.value=sLocation;

    //forcing edit frame to reload after submit
    top.forceReloadingEditFrame();
}

[{ if $updatemain }]
    UpdateMain('[{ $oxid }]');
[{ /if}]

function UpdateMain( sID)
{
    var oTransfer = parent.edit.document.getElementById("transfer");
    oTransfer.oxid.value=sID;
    oTransfer.cl.value='[{ $default_edit }]';

    //forcing edit frame to reload after submit
    top.forceReloadingEditFrame();
}


window.onLoad = top.reloadEditFrame();

//-->
</script>

<form name="search" id="search" action="[{ $oViewConf->getSelfLink() }]" method="post">

[{include file="pagetabsnippet.tpl"}]

<script type="text/javascript">
if (parent.parent )
{   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->value}]";
    parent.parent.sMenuItem    = "[{ oxmultilang ident="SHOP_LIST_MENUITEM" }]";
    parent.parent.sMenuSubItem = "[{ oxmultilang ident="SHOP_LIST_MENUSUBITEM" }]";
    parent.parent.sWorkArea    = "[{$_act}]";
    parent.parent.setTitle();
}
</script>
</body>
</html>
