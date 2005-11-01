<!-- BEGIN DYNAMIC BLOCK: note_row -->
<a class="date" href="{PERMA_LINK}">{DATE}</a><br />
<b>{NEWS_TITLE}</b><br /><br />
{NEWS_TEXT}<br />
<div class="right">
<!-- IFDEF: COMMENTS_ALLOW -->
    <!-- IFDEF: COMMENTS -->
    <a class="comments" href="{COMMENTS_LINK}">komentarze ({COMMENTS})</a>
    <!-- ELSE -->
    <a class="comments" href="{COMMENTS_LINK}">skomentuj ten post</a>
    <!-- ENDIF -->
<!-- ELSE -->

<!-- ENDIF -->
</div>
<div class="author">
	{NEWS_AUTHOR}
</div>
<div class="category">
	<b>Kategoria:</b>
	<!-- BEGIN DYNAMIC BLOCK: cat_row -->
	<a href="{CATEGORY_LINK}">{CATEGORY_NAME}</a>{COMMA}
	<!-- END DYNAMIC BLOCK: cat_row -->
</div>
<!-- IFDEF: RETURN -->
<div class="right">
	<a href="javascript:history.back()">{RETURN}</a>
</div>
<!-- ELSE -->
<br />
<!-- ENDIF -->
<!-- END DYNAMIC BLOCK: note_row -->
