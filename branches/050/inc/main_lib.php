<?php
// $Id: main_lib.php 1128 2005-08-03 22:16:55Z mysz $

function get_breadcrumb($page_id, $level) {
	
	global 
        $ft, 
        $rewrite, 
        $pages_sort, 
        $pages_id;

	$query = sprintf("
        SELECT 
            id, 
            parent_id, 
            title 
        FROM 
            %1\$s 
        WHERE 
            id = '%2\$d' 
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
		
	while($db->next_record()) {
	
		$page_id 	= $db->f("id");
		$parent_id 	= $db->f("parent_id");
		$page_name 	= $db->f("title");
		$page_link  = (bool)$rewrite ? '1,' . $page_id . ',5,item.html' : 'index.php?p=5&amp;id=' . $page_id . '';
	
		$ft->assign(array(
            'PAGE_TITLE'    =>$page_name,
            'PAGE_ID'       =>$page_id,
            'CLASS'         =>"child",
            'PARENT'        =>str_repeat('&nbsp; ', $level), 
            'PAGE_LINK'     =>$page_link
        ));
        
        $pages_sort[]   = $page_name;
        $pages_id[]     = $page_id;

		get_breadcrumb($parent_id, $level+2);
	}
}


function get_cat($page_id, $level) {
	
	global 
        $ft, 
        $rewrite;

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
		
	while($db->next_record()) {
	
		$page_id 	= $db->f("id");
		$parent_id 	= $db->f("parent_id");
		$page_name 	= $db->f("title");
		$page_link  = (bool)$rewrite ? '1,' . $page_id . ',5,item.html' : 'index.php?p=5&amp;id=' . $page_id . '';
	
		$ft->assign(array(
            'PAGE_NAME' =>$page_name,
            'PAGE_ID'   =>$page_id,
            'CLASS'     =>"child",
            'PARENT'    =>str_repeat('&nbsp; ', $level), 
            'PAGE_LINK' =>$page_link
        ));

		$ft->parse('PAGES_ROW', ".pages_row");
		get_cat($page_id, $level+2);
	}
}


// funkcja pobierajaca rekurencyjnie kategorie na stronie g³ównej
function get_category_cat($cat_id, $level) {
	
	global 
        $ft, 
        $rewrite;

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
        $cat_id
    );

	$db = new DB_SQL;
	$db->query($query);
		
	while($db->next_record()) {
	
		$cat_id           = $db->f("category_id");
		$cat_parent_id    = $db->f("category_parent_id");
		$cat_name         = $db->f("category_name");
		$cat_link         = (bool)$rewrite ? '1,' . $cat_id . ',4,item.html' : 'index.php?p=4&amp;id=' . $cat_id . '';
	
		$ft->assign(array(
            'CAT_NAME'  =>$cat_name,
            'NEWS_CAT'  =>$cat_id,
            'CLASS'     =>"cat_child",
            'PARENT'    =>str_repeat('&nbsp; ', $level), 
            'CAT_LINK'  =>$cat_link
        ));

		$ft->parse('CATEGORY_ROW', ".category_row");
		get_category_cat($cat_id, $level+2);
	}
}


function show_me_more($text) {
    
    global 
        $perma_link, 
        $i18n;
    
	if($find = strpos($text, '[podziel]') OR $find = strpos($text, '[more]')) {
	        
        $text = sprintf('%s<br /><a href="%s">%s</a>',
        
            substr($text, 0, $find),
            $perma_link,
            $i18n['main_view'][2]
        );
	}
	
	return $text;
}


function get_comments_link($comments_allow, $comments, $id) {
    
    global 
        $ft, 
        $rewrite;
    
    if(($comments_allow) == 0 ) {
        $ft->assign(array(
            'COMMENTS_ALLOW'    =>false, 
            'COMMENTS'          =>''
        ));
    } else {
        if($comments == 0) {
            $comments_link = (bool)$rewrite ? '1,' . $id . ',3,item.html' : 'index.php?p=3&amp;id=' . $id . '';
            $ft->assign(array(
                'COMMENTS_LINK' =>$comments_link, 
                'COMMENTS_ALLOW'=>true, 
                'COMMENTS'      =>''
            ));
	    } else {
            $comments_link = (bool)$rewrite ? '1,' . $id . ',2,item.html' : 'index.php?p=2&amp;id=' . $id . '';
            $ft->assign(array(
                'COMMENTS_LINK' =>$comments_link, 
                'COMMENTS_ALLOW'=>true, 
                'COMMENTS'      =>$comments
            ));
	    }
    }
}


function get_image_status($image, $id) {
    
    global 
        $ft, 
        $max_photo_width, 
        $rewrite;
    
    if(empty($image)) {
        // IFDEF: IMAGE_EXIST zwraca pusta wartosc, przechodzimy
        // do warunku ELSE
        $ft->assign(array(
            'IMAGE'         =>'', 
            'IMAGE_EXIST'   =>false, 
            'IMAGE_NAME'    =>false
        ));
    } else {
        
        $img_path = get_root() . '/photos/' . $image;
        
        if(is_file($img_path)) {
            
            list($width, $height) = getimagesize($img_path);
            
            $photo_link = (bool)$rewrite ? 'photo?id=' . $id . '' : 'photo.php?id=' . $id . '';
            
            // wysoko¶æ, szeroko¶æ obrazka
            $ft->assign(array(
                'WIDTH'         =>$width,
                'HEIGHT'        =>$height,
                'PHOTO_LINK'    =>$photo_link
            ));
            
            if($width > $max_photo_width) {
                
                $ft->assign(array(
                    'UID'           =>$id,
                    'IMAGE_NAME'    =>''
                ));
            } else {
                $ft->assign('IMAGE_NAME', $image);
            }
            
            $ft->assign('IMAGE_EXIST', true);
        } else {
            
            $ft->assign(array(
                'IMAGE_EXIST'   =>false, 
                'IMAGE_NAME'    =>false
            ));
        }
    }
}


function list_assigned_categories($id) {
    
    global 
        $ft, 
        $rewrite;
    
    $query = sprintf("
        SELECT 
            a.*, b.* 
        FROM 
            %1\$s a 
        LEFT JOIN 
            %2\$s b 
        ON 
            a.category_id = b.category_id 
        WHERE 
            a.news_id = '%3\$d'", 
	    
        TABLE_ASSIGN2CAT, 
        TABLE_CATEGORY, 
        $id
    );
	    
    $sql = new DB_SQL;
    $sql->query($query);
    
    $count_cats = $sql->nf();
    $idx = 1;
    
    while($sql->next_record()) {
        
        $cname = replace_amp($sql->f('category_name'));
        $cid   = $sql->f('category_id');
        
        $category_link  = (bool)$rewrite ? sprintf('1,%s,4,item.html', $cid) : 'index.php?p=4&amp;id=' . $cid;
        
        $ft->assign(array(
            'CATEGORY_NAME' =>$cname, 
            'CATEGORY_LINK' =>$category_link, 
            'COMMA'         =>$count_cats == $idx ? '' : ', '
        ));
        
        $ft->parse('CAT_ROW', ".cat_row");
        
        $idx++;
    }
    
    // CAT_ROW musi byc czyste
    $ft->clear_parse('CAT_ROW');
}


function coreMakeClickable($text) {

	$ret = ' ' . $text;

	$text = preg_replace("#(^|[\n ])([\w]+?://[^ \"\n\r\t<]*)#is", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $text);
	$text = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#is", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $text);
	$text = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $text);

	// Remove our padding..
	//$text = substr($text, 1);

	return($text);
}

?>
