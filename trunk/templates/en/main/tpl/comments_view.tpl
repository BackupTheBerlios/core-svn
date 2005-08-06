<!-- NAME: comments_view.tpl -->
<b><a class="date" href="{SITE_ROOT}/{PERMA_LINK}">{NEWS_TITLE}</a></b> - perma link<br /><br />
<!-- BEGIN DYNAMIC BLOCK: comments_row -->
<dl>
    <dt>{DATE}</dt>
    <dd>{COMMENTS_TEXT}<br />
    <div class="right">
        <a class="comments" href="{SITE_ROOT}/{QUOTE_LINK}">quote</a>
    </div>
    <div class="author">{COMMENTS_AUTHOR}</div>
    </dd>
</dl>
<!-- END DYNAMIC BLOCK: comments_row -->
<a class="comments" href="{SITE_ROOT}/{SUBMIT_LINK}">Add comment</a>
<!-- END: comments_view.tpl -->