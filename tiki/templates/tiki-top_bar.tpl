{if $prefs.feature_topbar_version eq 'y'}
{tr}This is{/tr} Tikiwiki v1.10.1 (CVS) -Arcturus- &#169; 2002&#8211;2007 {tr}by the{/tr} <a href="http://tikiwiki.org" 
title="tikiwiki.org">{tr}Tiki community{/tr}</a>
{/if}
{if $prefs.feature_topbar_date eq 'y'}
{if $prefs.feature_calendar eq 'y' and $tiki_p_view_calendar eq 'y'}
  <a href="tiki-calendar.php">{$smarty.now|tiki_short_datetime}</a>
{else}
  {$smarty.now|tiki_short_datetime}
{/if}
{/if}
{if $prefs.feature_topbar_debug eq 'y' and $tiki_p_admin eq 'y' and $prefs.feature_debug_console eq 'y'}
  &#160;//&#160;<a href="javascript:toggle('debugconsole');">{tr}debug{/tr}</a>
{/if}
{if $prefs.feature_phplayers eq 'y' and $prefs.feature_siteidentity eq 'y' and $prefs.feature_sitemenu eq 'y'}
{phplayers id=$prefs.feature_topbar_id_menu type=horiz}
{/if}
{if $prefs.feature_tell_a_friend eq 'y' && $tiki_p_tell_a_friend eq 'y'}
<div class="tellafriend"><a href="tiki-tell_a_friend.php?url={$smarty.server.REQUEST_URI|escape:'url'}">{tr}Email this page{/tr}</a></div>
{/if}
