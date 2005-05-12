<!-- BEGIN DYNAMIC BLOCK: note_row -->
<a class="date" href="{PERMA_LINK}">{DATE}</a><br />
<b>{NEWS_TITLE}</b><br /><br />
{NEWS_TEXT}<br />
{IMAGE}
<div class="right">
{COMMENTS_ALLOW}
</div>
<div class="author">
	{NEWS_AUTHOR}
</div>
<div class="category">
	<b>Kategoria:</b> <a href="{CATEGORY_LINK}">{CATEGORY_NAME}</a>
</div>
<!-- IFDEF: RETURN -->
<div class="right">
	<a href="javascript:history.back()">{RETURN}</a>
</div>
<!-- ELSE -->
<br />
<!-- ENDIF -->
<!-- END DYNAMIC BLOCK: note_row -->