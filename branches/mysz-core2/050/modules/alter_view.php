<?php
// $Id: alter_view.php 1213 2005-11-05 13:03:06Z mysz $

/*
 * This file is internal part of Core CMS (http://core-cms.com/) engine.
 *
 * Copyright (C) 2004-2005 Core Dev Team (more info: docs/AUTHORS).
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published
 * by the Free Software Foundation; version 2 only.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 */

$CoreNews = new CoreNews();
$CoreNews->news_get($_GET['id']);

if(count($CoreNews->news)) {

    $news   =& end($CoreNews->news);
    
    $id     = $news->get_id();
    $text   = str_replace(array('[podziel]', '[more]'), '', $news->get_text());
    $text   = preg_replace("/\[code:\"?([a-zA-Z0-9\-_\+\#\$\%]+)\"?\](.*?)\[\/code\]/sie", "highlighter('\\2', '\\1')", $text);

    $ft->define_dynamic('cat_row', 'rows');
    
    list_assigned_categories($_GET['id']);
	
    $ft->assign(array(
        'DATE'          =>date($date_format, $news->get_timestamp()),
        'NEWS_TITLE'    =>$news->get_title(),
        'NEWS_TEXT'     =>$text,
        'NEWS_AUTHOR'   =>$news->get_author(),
        'NEWS_ID'       =>$_GET['id'],
        'STRING'        =>'', 
        'PERMA_LINK'    =>$CoreRewrite->permanent_news($id, $rewrite), 
        'PAGINATED'     =>false, 
        'MOVE_BACK'     =>false, 
        'MOVE_FORWARD'  =>false
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
