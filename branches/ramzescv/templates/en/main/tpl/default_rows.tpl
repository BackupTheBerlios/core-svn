<!-- BEGIN DYNAMIC BLOCK: note_row -->
<a class="date" href="{PERMA_LINK}">{DATE}</a><br />
<b>{NEWS_TITLE}</b><br /><br />
{NEWS_TEXT}<br />
<!-- IFDEF: IMAGE_EXIST -->
    <!-- IFDEF: IMAGE_NAME -->
    <div align="center">
	   <img alt="./dev-log" src="photos/{IMAGE_NAME}" width="{WIDTH}" height="{HEIGHT}" style="padding: 7px;" />
    </div>
    <!-- ELSE -->
    <div id="image">
	   Attached image is too big &amp; cannot be displayed here. 
	   <a href="javascript:foto('{PHOTO_LINK}', {WIDTH}, {HEIGHT});">View</a> in 
	   new window.
    </div>
    <!-- ENDIF -->
<!-- ELSE -->

<!-- ENDIF -->
<div class="right">
<!-- IFDEF: COMMENTS_ALLOW -->
    <!-- IFDEF: COMMENTS -->
    <a class="comments" href="{COMMENTS_LINK}">comments ({COMMENTS})</a>
    <!-- ELSE -->
    <a class="comments" href="{COMMENTS_LINK}">leave a word</a>
    <!-- ENDIF -->
<!-- ELSE -->

<!-- ENDIF -->
</div>
<div class="author">
	{NEWS_AUTHOR}
</div>
<div class="category">
	<b>Category:</b>
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