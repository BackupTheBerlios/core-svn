<!-- NAME: comments_view.tpl -->
<b><a class="date" href="{PERMA_LINK}">{NEWS_TITLE}</a></b> - link bezpo¶redni<br /><br />
<!-- BEGIN DYNAMIC BLOCK: comments_row -->
<dl>
    <dt>{DATE}</dt>
    <dd>{COMMENTS_TEXT}<br />
    <div class="right">
        <a class="comments" href="{QUOTE_LINK}">odpowiedz cytuj±c</a>
    </div>
    <div class="author">{COMMENTS_AUTHOR}</div>
    </dd>
</dl>
<!-- END DYNAMIC BLOCK: comments_row -->
<a class="comments" href="{SUBMIT_LINK}">Dodaj komentarz</a>
<!-- END: comments_view.tpl -->