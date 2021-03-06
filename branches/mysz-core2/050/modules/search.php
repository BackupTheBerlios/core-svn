<?php
// $Id: search.php 1213 2005-11-05 13:03:06Z mysz $

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

require_once(PATH_TO_CLASSES. '/cls_search.php');
$search = new search();

$page_string = empty($page_string) ? '' : $page_string;

// inicjowanie zmiennej przechowującej szukaną frazę
$search_word = trim($_REQUEST['search_word']);
$mainposts_per_page = get_config('mainposts_per_page');

// zliczamy sume postow
$query = sprintf("
    SELECT COUNT(*) AS 
        id 
    FROM 
        %1\$s a 
    LEFT JOIN 
        %2\$s b 
    ON 
        a.id = b.news_id 
    WHERE 
        published = 1 
    AND 
        a.text LIKE '%%" . $search_word . "%%' 
    OR 
        a.title LIKE '%%" . $search_word . "%%' 
    ORDER BY 
        date", 
	
    TABLE_MAIN, 
    TABLE_ASSIGN2CAT
);

$db->query($query);
$db->next_record();
	
$num_items = $db->f("0");

// inicjowanie funkcji stronnicującej wpisy
$pagination = pagination($CoreRewrite->search_pagination($search_word, $rewrite), $mainposts_per_page, $num_items);

if(!empty($search_word)) {
	
    $query = sprintf("
        SELECT 
            a.*,
            UNIX_TIMESTAMP(a.date) AS date,
            c.id_news,
            count(c.id) AS comments 
        FROM 
            %1\$s a 
        LEFT JOIN 
            %3\$s c 
        ON 
            a.id = c.id_news
        WHERE 
            published = 1 
        AND 
            a.text LIKE '%%" . $search_word . "%%' 
        OR 
            a.title LIKE '%%" . $search_word . "%%' 
        GROUP BY 
            a.date 
        DESC 
        LIMIT %4\$d, %5\$d",
    
        TABLE_MAIN, 
        TABLE_CATEGORY, 
        TABLE_COMMENTS,
        $start,
        $mainposts_per_page
    );
	
	$db->query($query);
	
	if($db->num_rows() !== 0) {
	
	    $ft->define_dynamic("cat_row", "rows");
	    
		while($db->next_record()) {
			
			$date 			= date($date_format, $db->f("date"));
			$title 			= $db->f("title");
			$text 			= $db->f("text");
			$author 		= $db->f("author");
			$id 			= $db->f("id");
			$image			= $db->f("image");
			$comments_allow = $db->f("comments_allow");
			
			// usuwamy <a />
			$text = preg_replace('/(?is)(<\/?(?:a)(?:|\s.*?)>)/', '', $text);
			$text = preg_replace("/\[code:\"?([a-zA-Z0-9\-_\+\#\$\%]+)\"?\](.*?)\[\/code\]/sie", "highlighter('\\2', '\\1')", $text);
			
			$comments 	= $db->f("comments");
			
            list_assigned_categories($id);
			
			$ft->assign(array(
                'DATE'				=>$date,
                'NEWS_TITLE'		=>$search->highlight($search_word, $title),
                'NEWS_TEXT'			=>$search->highlight($search_word, $text),
                'NEWS_AUTHOR'		=>$author,
                'NEWS_ID'			=>$id,
                'PERMA_LINK'        =>$CoreRewrite->permanent_news($id, $rewrite), 
                'PAGINATED'         =>!empty($pagination['page_string']) ? true : false, 
                'STRING'            =>$pagination['page_string']
            ));
								
			get_comments_link($comments_allow, $comments, $id);
			get_image_status($image, $id);
            
			// definiujemy blok dynamiczny szablonu
			$ft->define_dynamic("note_row", "rows");
			
			$ft->assign('RETURN', '');
			$ft->parse('MAIN', ".note_row");
		} 
	} else {
	
		$ft->assign(array(
            'QUERY_FAILED'  =>sprintf('%s <span class="search">%s</span>.', $i18n['search'][0], $_POST['search_word']),
            'STRING'        =>$page_string, 
            'PAGINATED'     =>false, 
            'MOVE_BACK'     =>false, 
            'MOVE_FORWARD'  =>false
        ));
						
		$ft->parse('MAIN',".query_failed");
	}
} else {
	
	$ft->assign(array(
        'QUERY_FAILED'  =>$i18n['search'][1],
        'STRING'        =>'', 
        'PAGINATED'     =>false, 
        'MOVE_BACK'     =>false, 
        'MOVE_FORWARD'  =>false
    ));
						
	$ft->parse('MAIN',".query_failed");	
}

?>
