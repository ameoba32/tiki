<a class="pagetitle" href="tiki-my_tiki.php">{tr}My Tiki{/tr}</a><br/><br/>
{include file=tiki-mytiki_bar.tpl}
<br/><br/>
{if $mytiki_pages eq 'y'}
  <div class="cbox">
  <div class="cbox-title">{tr}User Pages{/tr}</div>
  <div class="cbox-data">
  <table width="100%">
  {section name=ix loop=$user_pages}
  <tr><td>
  <a class="link" title="{$user_pages[ix].pageName}" href="tiki-index.php?page={$user_pages[ix].pageName}">{$user_pages[ix].pageName|truncate:30:"(...)"}</a>
  </td><td align="right">
  (<a class="link" href="tiki-editpage.php?page={$user_pages[ix].pageName}">{tr}edit{/tr}</a>)
  </td></tr>
  {/section}
  </table>
  </div>
  </div>
<br/>  
{/if}
{if $mytiki_blogs eq 'y'}
  <div class="cbox">
  <div class="cbox-title">{tr}User Blogs{/tr}</div>
  <table width="100%">
  {section name=ix loop=$user_blogs}
  <tr><td>
  <a class="link" href="tiki-view_blog.php?blogId={$user_blogs[ix].blogId}">{$user_blogs[ix].title}</a>
  </td><td align="right">
  (<a class="link" href="tiki-edit_blog.php?blogId={$user_blogs[ix].blogId}">{tr}edit{/tr}</a>)
  </td></tr>
  {/section}
  </table>
  </div>
<br/>
{/if}
{if $mytiki_gals eq 'y'}
  <div class="cbox">
  <div class="cbox-title">{tr}User Galleries{/tr}</div>
  <div class="cbox-data">
  <table width="100%">
  {section name=ix loop=$user_galleries}
  <tr><td>
  <a class="link" href="tiki-browse_gallery.php?galleryId={$user_galleries[ix].galleryId}">{$user_galleries[ix].name}</a>
  </td><td align="right">
  <a class="link" href="tiki-galleries.php?editgal={$user_galleries[ix].galleryId}">({tr}edit{/tr})</a>
  </td></tr>
  {/section}
  </table>
  </div>
  </div>
<br/>
{/if}
{if $mytiki_items eq 'y'}
  <div class="cbox">
  <div class="cbox-title">{tr}Assigned items{/tr}</div>
  <div class="cbox-data">
  <table width="100%">
  {section name=ix loop=$user_items}
  <tr><td>
  <b>{$user_items[ix].value}</b> {tr}at tracker{/tr} {$user_items[ix].name}  
  </td><td align="right">
  <a class="link" href="tiki-view_tracker_item.php?trackerId={$user_items[ix].trackerId}&amp;itemId={$user_items[ix].itemId}">({tr}edit{/tr})</a>
  </td>
  </tr>
  {/section}
  </table>
  </div>
  </div>
<br/>
{/if}
{if $mytiki_msgs eq 'y'}
  <div class="cbox">
  <div class="cbox-title">{tr}Unread Messages{/tr}</div>
  <table width="100%">
  {section name=ix loop=$msgs}
  <tr><td>
  <a class="link" href="messu-read.php?offset=0&amp;flag=&amp;flagval=&amp;find=&amp;sort_mode=date_desc&amp;priority=&amp;msgId={$msgs[ix].msgId}">{$msgs[ix].subject}</a>
  </td></tr>
  {/section}
  </table>
  </div>
<br/>
{/if}
{if $mytiki_tasks eq 'y'}
  <div class="cbox">
  <div class="cbox-title">{tr}Tasks{/tr}</div>
  <table width="100%">
  {section name=ix loop=$tasks}
  <tr><td>
  <a class="link" href="tiki-user_tasks.php?taskId={$tasks[ix].taskId}">{$tasks[ix].title}</a>
  </td></tr>
  {/section}
  </table>
  </div>
{/if}
