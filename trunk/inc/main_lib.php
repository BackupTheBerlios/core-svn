<?php
// $Id$

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
        
        $ft->assign(array(
            'CATEGORY_NAME' =>$cname, 
            'CATEGORY_LINK' =>category_link($rewrite, $cid), 
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


// news permanent link
function perma_link($rewrite, $id) {
    
    $perma_link = (bool)$rewrite ? sprintf('%s', $id) : 'index.php?p=1&amp;id=' . $id;
    
    return $perma_link;
}


// pagination link
function pagination_link($rewrite) {
    
    $pagination_link = (bool)$rewrite ? 'offset/' : 'index.php?p=all&amp;start=';
    
    return $pagination_link;
}


// category link
function category_link($rewrite, $id) {
    
    $category_link = (bool)$rewrite ? sprintf('category/%s', $id) : 'index.php?p=4&amp;id=' . $id;
    
    return $category_link;
}

// category pagination link
function category_pagination_link($rewrite, $id) {
    
    $category_pagination_link = (bool)$rewrite ? sprintf('category/%s/', $id) : 'index.php?p=4&amp;id=' . $id . '&amp;start=';
    
    return $category_pagination_link;
}


// comments quote link
function comments_quote_link($rewrite, $comments_id, $id) {
    
    $comments_quote_link = (bool)$rewrite ? sprintf('addcomments/%s/quote/%s/', $comments_id, $id) : sprintf('index.php?p=3&amp;id=%s&amp;c=%s', $comments_id, $id);
    
    return $comments_quote_link;
}


// addcomments link
function addcomments_link($rewrite, $id) {
    
    $addcomments_link = (bool)$rewrite ? sprintf('addcomments/%s', $id) : 'index.php?p=3&amp;id=' . $id;
    
    return $addcomments_link;
}


// showcomments link
function showcomments_link($rewrite, $id) {
    
    $showcomments_link = (bool)$rewrite ? sprintf('comments/%s', $id) : 'index.php?p=2&amp;id=' . $id;
    
    return $showcomments_link;
}


// template_switcher link
function template_switcher_link($rewrite, $issue) {
    
    $template_switcher_link = (bool)$rewrite ? sprintf('switch/%s', $issue) : 'design.php?issue=' . $issue;
    
    return $template_switcher_link;
}


// search link
function search_link($rewrite) {
    
    $search_link = (bool)$rewrite ? 'search' : 'index.php?p=8';
    
    return $search_link;
}


// form link
function form_link($rewrite) {
    
    $form_link = (bool)$rewrite ? 'add/3' : 'index.php?p=3&amp;action=add';
    
    return $form_link;
}


// search pagination link
function search_pagination_link($rewrite, $word) {
    
    $search_pagination_link = (bool)$rewrite ? sprintf('search/%s/', $word) : sprintf('index.php?p=8&search_word=%s&amp;start=', $word);
    
    return $search_pagination_link;
}


// date link
function date_link($rewrite, $month, $day) {
    
    $date_link = (bool)$rewrite ? sprintf('<a href="' .SITE_ROOT . '/date/%s-%s">%s</a>', $month, $day, $day) : sprintf('<a href="' .SITE_ROOT . '/index.php?p=9&amp;date=%s-%s">%s</a>', $month, $day, $day);
    
    return $date_link;
}


// show_all link
function category_all_link($rewrite) {
    
    $category_all_link = (bool)$rewrite ? 'all' : 'index.php?p=all';
    
    return $category_all_link;
}


// page link
function page_link($rewrite, $id) {
    
    $page_link = (bool)$rewrite ? sprintf('node/%s', $id) : 'index.php?p=5&amp;id=' . $id;
    
    return $page_link;
}


// photo link
function photo_link($rewrite, $id) {
    
    $photo_link = (bool)$rewrite ? sprintf('photo/%s', $id) : 'index.php?id=' . $id;
    
    return $photo_link;
}

// page photo link
function page_photo_link($rewrite, $id) {
    
    $page_photo_link = (bool)$rewrite ? sprintf('page/photo/%s', $id) : 'index.php?p=5id=' . $id;
    
    return $page_photo_link;
}

?>