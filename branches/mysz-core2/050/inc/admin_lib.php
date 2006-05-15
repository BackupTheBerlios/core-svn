<?php
// $Id: admin_lib.php 1213 2005-11-05 13:03:06Z mysz $

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

function get_addcategory_assignedcat($page_id, $level) {
	
	global $ft;

	$query = sprintf("
        SELECT 
            category_id, 
            category_parent_id, 
            category_name 
        FROM 
            %1\$s 
        WHERE 
            category_parent_id = '%2\$d' 
        ORDER BY 
            category_id 
        ASC", 
	
        TABLE_CATEGORY, 
        $page_id
    );

	$db = new DB_SQL;
	$db->query($query);
	
	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		
	while($db->next_record()) {
	
		$cat_id           = $db->f("category_id");
		$cat_parent_id    = $db->f("category_parent_id");
		$cat_name         = $db->f("category_name");
	
		$ft->assign(array(
            'C_ID'          =>$cat_id, 
            'C_NAME'        =>$cat_name,
            'CURRENT_CAT'   =>'', 
            'PAD'           =>'style="padding-left:' . 8*$level . 'px;" '
        ));

        $ft->parse('CAT_ROW', ".cat_row");
		
		get_addcategory_assignedcat($cat_id, $level+2);
	}
}


// funkcja pobierajaca rekurencyjnie kategorie::edycja newsa
function get_editnews_assignedcat($c_id, $level) {
	
	global 
        $ft, 
        $category, 
        $sql;

	$query = sprintf("
        SELECT 
            category_id, 
            category_parent_id, 
            category_name 
        FROM 
            %1\$s 
        WHERE 
            category_parent_id = '%2\$s' 
        ORDER BY 
            category_id 
        ASC", 
	
        TABLE_CATEGORY, 
        $c_id
    );

	$db = new DB_SQL;
	$db->query($query);
	
	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		
	while($db->next_record()) {
	
		$cat_id           = $db->f("category_id");
		$cat_parent_id    = $db->f("category_parent_id");
		$cat_name         = $db->f("category_name");
		
		$query = sprintf("
            SELECT * FROM 
                %1\$s 
            WHERE 
                category_id = '%2\$d' 
            AND 
                news_id = '%3\$d'", 
		
            TABLE_ASSIGN2CAT, 
            $cat_id, 
            $_GET['id']
        );
        
        $sql->query($query);
        $sql->next_record();
	
		$ft->assign(array(
            'C_ID'          =>$cat_id,
            'PAD'           =>'style="padding-left:' . 8*$level . 'px;" ', 
            'C_NAME'        =>$cat_name, 
            'CURRENT_CAT'   =>$cat_id == ($assigned = $sql->f("category_id")) ? 'checked="checked"' : ''
        ));
        
        $ft->parse('CAT_ROW', ".cat_row");
		
		get_editnews_assignedcat($cat_id, $level+2);
	}
}


function get_addpage_cat($page_id, $level, $current_id = 0, $pageid_prefix = '') {
	
	global $ft;

	$query = sprintf("
        SELECT 
            id, 
            parent_id, 
            title 
        FROM 
            %1\$s 
        WHERE 
            parent_id = '%2\$d' 
        AND 
            published = 'Y' 
        ORDER BY 
            id 
        ASC", 
	
        TABLE_PAGES, 
        $page_id
    );

	$db = new DB_SQL;
	$db->query($query);
	
	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		
	while($db->next_record()) {
	
		$page_id 	= $db->f("id");
		$parent_id 	= $db->f("parent_id");
		$title 		= $db->f("title");
	
		$ft->assign(array(
            'P_ID'      =>$pageid_prefix . $page_id,
            'P_NAME'    =>str_repeat('&nbsp; ', $level) . "- " .$title,
            'CURRENT'   =>$page_id == $current_id ? 'selected="selected"' : ''
        ));
        
        $ft->parse('PAGE_ROW', ".page_row");
		
		get_addpage_cat($page_id, $level+2, $current_id, $pageid_prefix);
	}
}


function get_editpage_cat($page_id, $level) {
	
	global 
        $ft, 
        $idx1, 
        $i18n;
        
    $lang = get_config('language_set');

	$query = sprintf("
        SELECT 
            id, 
            parent_id, 
            title, 
            published 
        FROM 
            %1\$s 
        WHERE 
            parent_id = '%2\$d' 
        ORDER BY 
            id 
        ASC", 
	
        TABLE_PAGES, 
        $page_id
    );

	$db = new DB_SQL;
	$db->query($query);
		
	while($db->next_record()) {
	
		$page_id 	= $db->f("id");
		$title 		= $db->f("title");
		$published	= $db->f("published");
	
		$ft->assign(array(
            'ID'        =>$page_id,
            'TITLE'     =>str_repeat('&nbsp; ', $level) . '<img src="templates/' . $lang . '/images/ar.gif" />&nbsp;' . $title, 
            'REORDER_DOWN'  =>false, 
            'REORDER_UP'    =>false, 
            'PUBLISHED' =>$published == 'Y' ? $i18n['confirm'][0] : $i18n['confirm'][1]
        ));
		
		// deklaracja zmiennej $idx1::color switcher
		$idx1 = empty($idx1) ? '' : $idx1;
				
		$idx1++;
		
        $ft->define("editlist_pages", "editlist_pages.tpl");
        $ft->define_dynamic("row", "editlist_pages");
			
		// naprzemienne kolorowanie wierszy tabeli
		$ft->assign('ID_CLASS', $idx1%2 ? 'mainList' : 'mainListAlter');
		
		$ft->parse('ROWS', ".row");
		
		get_editpage_cat($page_id, $level+2);
	}
}


// funkcja pobierajaca rekurencyjnie kategorie
function get_addcategory_cat($page_id, $level, $current_id = 0, $pageid_prefix = '') {
	
	global $ft;

	$query = sprintf("
        SELECT 
            category_id, 
            category_parent_id, 
            category_name 
        FROM 
            %1\$s 
        WHERE 
            category_parent_id = '%2\$d' 
        ORDER BY 
            category_id 
        ASC", 
	
        TABLE_CATEGORY, 
        $page_id
    );

	$db = new DB_SQL;
	$db->query($query);
	
	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		
	while($db->next_record()) {
	
		$cat_id           = $db->f("category_id");
		$cat_parent_id    = $db->f("category_parent_id");
		$cat_name         = $db->f("category_name");
	
		$ft->assign(array(
            'C_ID'		=>$pageid_prefix . $cat_id,
            'C_NAME'	=>str_repeat('&nbsp; ', $level) . "- " .$cat_name,
            'CURRENT'   =>$cat_id == $current_id ? 'selected="selected"' : ''
        ));

        $ft->parse('CATEGORY_ROW', ".category_row");
		
		get_addcategory_cat($cat_id, $level+2, $current_id, $pageid_prefix);
	}
}


// funkcja pobierajaca rekurencyjnie kategorie::transfer wpisow
function get_transfercategory_cat($page_id, $level) {
	
	global $ft;

	$query = sprintf("
        SELECT 
            category_id, 
            category_parent_id, 
            category_name 
        FROM 
            %1\$s 
        WHERE 
            category_parent_id = '%2\$d' 
        ORDER BY 
            category_id 
        ASC", 
	
        TABLE_CATEGORY, 
        $page_id
    );

	$db = new DB_SQL;
	$db->query($query);
		
	while($db->next_record()) {
	
		$cat_id           = $db->f("category_id");
		$cat_parent_id    = $db->f("category_parent_id");
		$cat_name         = $db->f("category_name");

        $ft->assign(array(
            'CURRENT_CID'   =>$cat_id,
            'TARGET_CID'    =>$cat_id,
            'CURRENT_CNAME' =>str_repeat('&nbsp; ', $level) . "- " .$cat_name,
            'TARGET_CNAME'  =>str_repeat('&nbsp; ', $level) . "- " .$cat_name
        ));

		$ft->parse('CURRENT_ROW', ".current_row");
        $ft->parse('TARGET_ROW', ".target_row");
		
		get_transfercategory_cat($cat_id, $level+2);
	}
}


// funkcja pobierajaca rekurencyjnie kategorie::lista kategorii
function get_editcategory_cat($category_id, $level) {
	
	global 
	   $ft, 
	   $idx1, 
	   $count, 
	   $i18n;
	   
	$lang = get_config('language_set');

	$query = sprintf("
        SELECT 
            a.*, count(b.id) AS count 
        FROM 
            %1\$s a 
        LEFT JOIN 
            %2\$s b 
        ON 
            a.category_id = b.category_id 
        WHERE 
            category_parent_id = '%3\$d'
        GROUP BY 
            category_id 
        ORDER BY 
            category_id 
        ASC", 
	
        TABLE_CATEGORY, 
        TABLE_ASSIGN2CAT,
        $category_id
        );

	$db = new DB_SQL;
	$db->query($query);
		
	while($db->next_record()) {
	
		$category_id          = $db->f("category_id");
		$category_name        = $db->f("category_name");
		$cat_parent_id        = $db->f("category_parent_id");
		$category_descrition  = $db->f("category_description");
		$count                = $db->f("count");
	
		$ft->assign(array(
            'CATEGORY_ID'   =>$category_id,
            'CATEGORY_NAME' =>str_repeat('&nbsp; ', $level) . '<img src="templates/' . $lang . '/images/ar.gif" />&nbsp;' . $category_name,
            'COUNT'         =>$count, 
            'REORDER_DOWN'  =>false, 
            'REORDER_UP'    =>false,  
            'CATEGORY_DESC' =>empty($category_description) ? $i18n['edit_category'][4] : $category_description
        ));
		
		// deklaracja zmiennej $idx1::color switcher
		$idx1 = empty($idx1) ? '' : $idx1;
				
		$idx1++;
			
		// naprzemienne kolorowanie wierszy tabeli
		$ft->assign('ID_CLASS', $idx1%2 ? 'mainList' : 'mainListAlter');
		
		$ft->parse('ROWS', ".row");
		
		get_editcategory_cat($category_id, $level+2);
	}
}


function parse_markers($text, $break = 0, $tab = 0, $tab_long = 4) {
    
    $pregResultArr      = array();
    $pregResultArrSize  = 0;
    $hash               = md5($text);
    $tempArr            = array();
    
    preg_match_all("#<(ul|li|ol)[^>]*?>.*?</(\\1)>#si", $text, $pregResultArr);
    
    $pregResultArrSize = sizeOf($pregResultArr[0]);
    
    for($i=0; $i<$pregResultArrSize; $i++){
        $tempArr[$i] = $hash.'_'.$i;
    }
    
    $text = str_replace($pregResultArr[0], $tempArr, $text);
    
    $break  == 1 ? $text = str_nl2br($text) : '';
    $tab    == 1 ? $text = str_replace("\t", str_repeat('&nbsp;', $tab_long), $text) : '';
    $text   = str_replace($tempArr, $pregResultArr[0], $text);
    
    return $text;
}

?>
