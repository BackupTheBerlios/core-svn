<?php

// $Id$

$mainposts_per_page = get_config('mainposts_per_page');

$CoreNews   = new CoreNews();
$num_items  = $CoreNews->news_count();

// inicjowanie funkcji stronnicuj±cej wpisy
$pagination = pagination($CoreRewrite->pagination($rewrite), $mainposts_per_page, $num_items);

// definiujemy blok dynamiczny szablonu
$ft->define_dynamic('note_row', 'rows');
$ft->define_dynamic('cat_row', 'rows');

$CoreNews->news_list(null);
if(count($CoreNews->news)) {
    
    foreach($CoreNews->news as $news) {
        
        $id = $news->get_id();
        list_assigned_categories($id);
        
        $text = show_me_more($news->get_text());
        $text = preg_replace("/\[code:\"?([a-zA-Z0-9\-_\+\#\$\%]+)\"?\](.*?)\[\/code\]/sie", "highlighter('\\2', '\\1')", $text);
	    
	    $ft->assign(array(
           'DATE'          =>date($date_format, $news->get_timestamp()),
           'NEWS_TITLE'    =>$news->get_title(),
	       'NEWS_TEXT'     =>$text,
           'NEWS_AUTHOR'   =>$news->get_author(),
	       'NEWS_ID'       =>$id,
	       'NEWS_CATEGORY' =>'', //TODO
	       'PERMA_LINK'    =>$CoreRewrite->permanent_news($id, $rewrite), 
	       'PAGINATED'     =>!empty($pagination['page_string']) ? true : false, 
	       'STRING'        =>$pagination['page_string']
	    ));
	    
        get_comments_link($news->get_comments_allow(), 0, $id); //TODO
	    
	    $ft->assign('RETURN', '');
	    $ft->parse('MAIN', '.note_row');
    }
} else {
    
    // Obs³uga b³êdu, kiedy w bazie danych nie ma jeszcze ¿adnego wpisu
    $ft->assign(array(
        'QUERY_FAILED'  =>$i18n['main_view'][1],
        'STRING'        =>'', 
        'PAGINATED'     =>false, 
        'MOVE_BACK'     =>false, 
        'MOVE_FORWARD'  =>false
    ));
    
    $ft->parse('MAIN', '.query_failed');
}

?>