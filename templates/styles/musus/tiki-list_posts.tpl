<a class="pagetitle" href="tiki-list_posts.php">{tr}Blogs{/tr}</a>
<!-- the help link info -->
      {if $feature_help eq 'y'}
<a href="http://tikiwiki.org/tiki-index.php?page=Blog" target="tikihelp" class="tikihelp" title="{tr}Tikiwiki.org help{/tr}: {tr}Blog{/tr}">
<img border='0' src='img/icons/help.gif' alt='help' /></a>{/if}
<!-- link to tpl -->
      {if $feature_view_tpl eq 'y'}
<a href="tiki-edit_templates.php?template=templates/tiki-list_posts.tpl" target="tikihelp" class="tikihelp" title="{tr}View tpl{/tr}: {tr}list posts tpl{/tr}">
<img border='0' src='img/icons/info.gif' alt='edit tpl' /></a>{/if}
<!-- beginning of next bit -->
<br /><br />
<a class="linkbut" href="tiki-edit_blog.php">{tr}edit blog{/tr}</a>
<a class="linkbut" href="tiki-blog_post.php">{tr}post{/tr}</a>
<a class="linkbut" href="tiki-list_blogs.php">{tr}list blogs{/tr}</a>
<br /><br />
<div align="center">
<table class="findtable">
<tr><td>{tr}Find{/tr}</td>
   <td>
   <form method="get" action="tiki-list_posts.php">
     <input type="text" name="find" value="{$find|escape}" />
     <input type="submit" value="{tr}find{/tr}" name="search" />
     <input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />
   </form>
   </td>
</tr>
</table>
<table>
<tr>
<th><a href="tiki-list_posts.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'postId_desc'}postId_asc{else}postId_desc{/if}">{tr}Id{/tr}</a></th>
<th>{tr}Blog Title{/tr}</th>
<th><a href="tiki-list_posts.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'created_desc'}created_asc{else}created_desc{/if}">{tr}Created{/tr}</a></th>
<th>{tr}Size{/tr}</th>
<th><a href="tiki-list_posts.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'user_desc'}user_asc{else}user_desc{/if}">{tr}User{/tr}</a></th>
<th>{tr}Action{/tr}</th>
</tr>
{section name=changes loop=$listpages}
{if $smarty.section.changes.index % 2}
<tr class="odd">
<td>&nbsp;{$listpages[changes].postId}&nbsp;</td>
<td>&nbsp;<a class="blogname" href="tiki-edit_blog.php?blogId={$listpages[changes].blogId}" title="{$listpages[changes].blogTitle}">{$listpages[changes].blogTitle|truncate:10:"(...)":true}</a>&nbsp;</td>
<td>&nbsp;{$listpages[changes].created|tiki_short_datetime}&nbsp;</td>
<td>&nbsp;{$listpages[changes].size}&nbsp;</td>
<td>&nbsp;{$listpages[changes].user}&nbsp;</td>
<td>
<a href="tiki-list_posts.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$listpages[changes].postId}">{tr}Remove{/tr}</a>
<a href="tiki-blog_post.php?postId={$listpages[changes].postId}">{tr}Edit{/tr}</a>
</td>
</tr>
{else}
<tr class="even">
<td>&nbsp;{$listpages[changes].postId}&nbsp;</td>
<td>&nbsp;<a class="blogname" href="tiki-edit_blog.php?blogId={$listpages[changes].blogId}" title="{$listpages[changes].blogTitle}">{$listpages[changes].blogTitle|truncate:10:"(...)":true}</a>&nbsp;</td>
<td>&nbsp;{$listpages[changes].created|tiki_short_datetime}&nbsp;</td>
<td>&nbsp;{$listpages[changes].size}&nbsp;</td>
<td>&nbsp;{$listpages[changes].user}&nbsp;</td>
<td>
<a href="tiki-list_posts.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$listpages[changes].postId}">{tr}Remove{/tr}</a>
<a href="tiki-blog_post.php?postId={$listpages[changes].postId}">{tr}Edit{/tr}</a>
</td>
{/if}
</tr>
{sectionelse}
<tr><td colspan="6">
<b>{tr}No records found{/tr}</b>
</td></tr>
{/section}
</table>
<br />
<div class="mini">
{if $prev_offset >= 0}
[<a class="prevnext" href="tiki-list_posts.php?find={$find}&amp;offset={$prev_offset}&amp;sort_mode={$sort_mode}">{tr}prev{/tr}</a>]&nbsp;
{/if}
{tr}Page{/tr}: {$actual_page}/{$cant_pages}
{if $next_offset >= 0}
&nbsp;[<a class="prevnext" href="tiki-list_posts.php?find={$find}&amp;offset={$next_offset}&amp;sort_mode={$sort_mode}">{tr}next{/tr}</a>]
{/if}
{if $direct_pagination eq 'y'}
<br />
{section loop=$cant_pages name=foo}
{assign var=selector_offset value=$smarty.section.foo.index|times:$maxRecords}
<a class="prevnext" href="tiki-list_posts.php?find={$find}&amp;offset={$selector_offset}&amp;sort_mode={$sort_mode}">{$smarty.section.foo.index_next}</a>
{/section}
{/if}
</div>
</div>
