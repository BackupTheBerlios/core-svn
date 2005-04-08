<!-- NAME: comments_view.tpl -->
<b><a class="date" href="1,{NEWS_ID},1,item.html">{NEWS_TITLE}</a></b> - link bezpo¶redni<br /><br />
<!-- BEGIN DYNAMIC BLOCK: comments_row -->
<dl>
    <dt>{DATE}</dt>
    <dd>{COMMENTS_TEXT}<br />
    <div class="right">
        <a class="comments" href="1,{COMMENTS_ID},3,{ID},1,quote.html">odpowiedz cytuj±c</a>
    </div>
    <div class="author">{COMMENTS_AUTHOR}</div>
    </dd>
</dl>
<!-- END DYNAMIC BLOCK: comments_row -->
<a class="comments" href="1,{NEWS_ID},3,item.html">Dodaj komentarz</a>
<!-- END: comments_view.tpl -->