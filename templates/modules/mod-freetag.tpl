{* $Header: /cvsroot/tikiwiki/tiki/templates/modules/mod-freetag.tpl,v 1.11 2007-10-04 22:17:46 nyloth Exp $ *}

{if $prefs.feature_freetags eq 'y' and $tiki_p_view_freetags eq 'y'}
{if !isset($tpl_module_title)}{assign value="{tr}Folksonomy{/tr}" var="tpl_module_title"}{/if}
{tikimodule title=$tpl_module_title name="freetag" flip=$module_params.flip decorations=$module_params.decorations}

{include file="freetag_list.tpl"}

<form name="addTags" method="post" action="{$smarty.server.REQUEST_URI}">
<input type="text" name="addtags" maxlength="40" />
<input type="submit" name="Add" value="Add" />
</form>
</div>
{/tikimodule}
{/if}
