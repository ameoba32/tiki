<a class="pagetitle" href="tiki-quiz_stats.php">{tr}Stats for quizzes{/tr}</a><br/><br/>
[<a class="link" href="tiki-list_quizzes.php">{tr}list quizzes{/tr}</a>
|<a class="link" href="tiki-quiz_stats.php">{tr}quiz stats{/tr}</a>
|<a class="link" href="tiki-edit_quiz.php">{tr}admin quizzes{/tr}</a>]<br/><br/>
<h2>{tr}Quizzes{/tr}</h2>
<div  align="center">
<table class="findtable">
<tr><td class="findtable">{tr}Find{/tr}</td>
   <td class="findtable">
   <form method="get" action="tiki-quiz_stats.php">
     <input type="text" name="find" value="{$find}" />
     <input type="submit" value="{tr}find{/tr}" name="search" />
     <input type="hidden" name="sort_mode" value="{$sort_mode}" />
     <input type="hidden" name="quizId" value="{$quizId}" />
   </form>
   </td>
</tr>
</table>
<table class="normal">
<tr>
<td class="heading"><a class="tableheading" href="tiki-quiz_stats.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'quizName_desc'}quizName_asc{else}quizName_desc{/if}">{tr}Quiz{/tr}</a></td>
<td class="heading"><a class="tableheading" href="tiki-quiz_stats.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'timesTaken_desc'}timesTaken_asc{else}timesTaken_desc{/if}">{tr}taken{/tr}</a></td>
<td class="heading"><a class="tableheading" href="tiki-quiz_stats.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'avgavg_desc'}avgavg_asc{else}avgavg_desc{/if}">{tr}Av score{/tr}</a></td>
<td class="heading"><a class="tableheading" href="tiki-quiz_stats.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'avgtime_desc'}avgtime_asc{else}avgtime_desc{/if}">{tr}Av time{/tr}</a></td>
</tr>
{section name=user loop=$channels}
{if ($tiki_p_admin eq 'y') or ($channels[user].individual eq 'n' and $tiki_p_view_quiz_stats eq 'y') or ($channels[user].individual_tiki_p_view_quiz_stats eq 'y')}
{if $smarty.section.user.index % 2}
<tr>
<td class="odd"><a class="tablename" href="tiki-quiz_stats_quiz.php?quizId={$channels[user].quizId}">{$channels[user].quizName}</a></td>
<td class="odd">{$channels[user].timesTaken}</td>
<td class="odd">{$channels[user].avgavg}%</td>
<td class="odd">{$channels[user].avgtime} secs</td>
</tr>
{else}
<tr>
<td class="even"><a class="tablename" href="tiki-quiz_stats_quiz.php?quizId={$channels[user].quizId}">{$channels[user].quizName}</a></td>
<td class="even">{$channels[user].timesTaken}</td>
<td class="even">{$channels[user].avgavg}%</td>
<td class="even">{$channels[user].avgtime} secs</td>
</tr>
{/if}
{/if}
{/section}
</table>
<div class="mini">
{if $prev_offset >= 0}
[<a class="prevnext" href="tiki-quiz_stats.php?quizId={$quizId}&amp;find={$find}&amp;offset={$prev_offset}&amp;sort_mode={$sort_mode}">{tr}prev{/tr}</a>]&nbsp;
{/if}
{tr}Page{/tr}: {$actual_page}/{$cant_pages}
{if $next_offset >= 0}
&nbsp;[<a class="prevnext" href="tiki-quiz_stats.php?quizId={$quizId}&amp;find={$find}&amp;offset={$next_offset}&amp;sort_mode={$sort_mode}">{tr}next{/tr}</a>]
{/if}
</div>
</div>

