{* $Header: /cvsroot/tikiwiki/tiki/templates/modules/mod-who_is_there.tpl,v 1.4 2003-11-20 23:49:04 mose Exp $ *}

<div class="box">
<div class="box-title">
{include file="module-title.tpl" module_title="{tr}Online users{/tr}" module_name="who_is_there"}
</div>
<div class="box-data">
<div class="user-box-text">{$logged_users} 
{if $logged_users>1}
{tr}online users{/tr}
{elseif $logged_users>0}
{tr}online user{/tr}
{/if}
</div>
{section name=ix loop=$online_users}
{if $user and $feature_messages eq 'y' and $tiki_p_messages eq 'y'}
<a class="linkmodule" href="messu-compose.php?to={$online_users[ix].user}" title="{tr}Send a message to{/tr} {$online_users[ix].user}"><img src="img/icons/icon_ultima.gif" width="18" height="9 hspace="2" vspace="0" border="0" alt="{tr}Send message{/tr}" /></a>
{/if}
{if $online_users[ix].user_information eq 'public'}
{math equation="x - y" x=$smarty.now y=$online_users[ix].timestamp assign=idle}
<a class="linkmodule" href="tiki-user_information.php?view_user={$online_users[ix].user}" title="{tr}More info about{/tr} {$online_users[ix].user} ({tr}idle{/tr} {$idle} {tr}seconds{/tr})">{$online_users[ix].user}</a><br/>
{else}
{$online_users[ix].user}<br/>
{/if}
{/section}
</div>
</div>

