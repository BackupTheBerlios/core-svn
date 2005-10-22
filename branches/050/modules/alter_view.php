<?php
// $Id: alter_view.php 1128 2005-08-03 22:16:55Z mysz $


$CoreNews = new CoreNews();
$CoreNews->news_get($_GET['id']);

if(count($CoreNews->news)) {

    $news =& end($CoreNews->news);

    $text = str_replace(array('[podziel]', '[more]'), '', $news->get_text());
	$text = preg_replace("/\[code:\"?([a-zA-Z0-9\-_\+\#\$\%]+)\"?\](.*?)\[\/code\]/sie", "highlighter('\\2', '\\1')", $text);

    $ft->define_dynamic('cat_row', 'rows');
    
    list_assigned_categories($_GET['id']);
    $perma_link = (bool)$rewrite ? sprintf('1,%s,1,item.html', $id) : 'index.php?p=1&amp;id=' . $_GET['id'];
	

    $ft->assign(array(
        'DATE'          => date($date_format, $news->get_timestamp()),
        'NEWS_TITLE'    => $news->get_title(),
        'NEWS_TEXT'     => $text,
        'NEWS_AUTHOR'   => $news->get_author(),
        'NEWS_ID'       => $_GET['id'],
        'STRING'        => '', 
        'PERMA_LINK'    => $perma_link, 
        'PAGINATED'     => false, 
        'MOVE_BACK'     => false, 
        'MOVE_FORWARD'  => false
    ));

    $comments = 0;
    get_comments_link($news->get_comments_allow(), $comments, $_GET['id']);
    
    // definiujemy blok dynamiczny szablonu
    $ft->define_dynamic('note_row', 'rows');
    
    $ft->assign('RETURN', $i18n['alter_view'][0]);
    $ft->parse('MAIN','.note_row');
} else {

    $ft->assign(array(
        'QUERY_FAILED'  =>$i18n['alter_view'][1],
        'STRING'        =>'', 
        'PAGINATED'     =>false, 
        'MOVE_BACK'     =>false, 
        'MOVE_FORWARD'  =>false
    ));

    $ft->parse('MAIN','.query_failed');
}

?>
