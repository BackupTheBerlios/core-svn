<!-- NAME: comments_view.tpl -->
<b><a class="date" href="{PERMA_LINK}">{NEWS_TITLE}</a></b> - permalink<br /><br />
<!-- BEGIN DYNAMIC BLOCK: comments_row -->
<dl>
    <dt>{DATE}</dt>
    <dd>{COMMENTS_TEXT}<br />
    <!-- IFDEF: SHOW_ADDCOMMENT -->
    <div class="right">
        <a class="comments" href="{QUOTE_LINK}">quote</a>
    </div>
    <!-- ENDIF -->
    <div class="author">{COMMENTS_AUTHOR}</div>
    </dd>
</dl>
<!-- END DYNAMIC BLOCK: comments_row -->
<!-- IFDEF: SHOW_ADDCOMMENT -->
<a class="comments" href="{SUBMIT_LINK}">Add comment</a>
<!-- ENDIF -->
<!-- END: comments_view.tpl -->
