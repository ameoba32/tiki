<div class="box">
<div class="box-title">
{tr}My galleries{/tr}
</div>
<div class="box-data">
{section name=ix loop=$modUserG}
<div class="button">{$smarty.section.ix.index_next})<a class="linkbut" href="tiki-browse_gallery.php?galleryId={$modUserG[ix].galleryId}">{$modUserG[ix].name}</a></div>
{/section}
</div>
</div>