{* $Header: /cvsroot/tikiwiki/tiki/templates/modules/mod-top_quizzes.tpl,v 1.5 2003-11-20 23:49:04 mose Exp $ *}

{if $feature_quizzes eq 'y'}
<div class="box">
<div class="box-title">
{include file="module-title.tpl" module_title="{tr}Top Quizzes{/tr}" module_name="top_quizzes"}
</div>
<div class="box-data">
<table  border="0" cellpadding="0" cellspacing="0">
{section name=ix loop=$modTopQuizzes}
<tr>{if $nonums != 'y'}<td class="module" valign="top">{$smarty.section.ix.index_next})</td>{/if}
<td class="module"><a class="linkmodule" href="tiki-take_quiz.php?quizId={$modTopQuizzes[ix].quizId}">{$modTopQuizzes[ix].quizName}</a></td></tr>
{/section}
</table>
</div>
</div>
{/if}