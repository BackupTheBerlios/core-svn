<?php
// $Id$

$pagination_link    = (bool)$rewrite ? 'index.' : 'index.php?p=all&amp;start=';
$mainposts_per_page = get_config('mainposts_per_page');

// zliczamy posty
$query = sprintf("
    SELECT 
        COUNT(*) AS id 
    FROM 
        %1\$s 
    WHERE 
        published = '1' 
    AND 
        only_in_category = '-1' 
    ORDER BY 
        date", 
	
    TABLE_MAIN
);

$db->query($query);
$db->next_record();
	
$num_items = $db->f("0");

// inicjowanie funkcji stronnicuj±cej wpisy
$pagination = pagination($pagination_link, $mainposts_per_page, $num_items);

$query = sprintf("
	SELECT 
        a.*,
        UNIX_TIMESTAMP(a.date) AS date,
		c.comments_id,
		count(c.id) AS comments 
	FROM 
		%1\$s a 
	LEFT JOIN 
		%3\$s c 
	ON 
		a.id = c.comments_id
	WHERE 
		published = 1 
    AND 
        only_in_category = -1 
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

// definiujemy blok dynamiczny szablonu
$ft->define_dynamic("note_row", "rows");
$ft->define_dynamic("cat_row", "rows");

// Sprawdzamy, czy w bazie danych s± ju¿ jakie¶ wpisy
if($db->num_rows() > 0) {

	while($db->next_record()) {
	    
	    $date              = date($date_format, $db->f("date"));
	    $title             = $db->f("title");
	    $text              = $db->f("text");
	    $author            = $db->f("author");
	    $id                = $db->f("id");
	    $c_id              = $db->f("c_id");
	    $image             = $db->f("image");
	    $comments_allow    = $db->f("comments_allow");
	    
	    $comments          = $db->f("comments");
	    
	    list_assigned_categories($id);
	    
	    $perma_link = (bool)$rewrite ? sprintf('1,%s,1,item.html', $id) : 'index.php?p=1&amp;id=' . $id;
        
        $text = show_me_more($text);
        $text = preg_replace("/\[code:\"?([a-zA-Z0-9\-_\+\#\$\%]+)\"?\](.*?)\[\/code\]/sie", "highlighter('\\2', '\\1')", $text);
	    
	    $ft->assign(array(
	       'DATE'          =>$date,
	       'NEWS_TITLE'    =>$title,
	       'NEWS_TEXT'     =>$text,
	       'NEWS_AUTHOR'   =>$author,
	       'NEWS_ID'       =>$id,
	       'NEWS_CATEGORY' =>$c_id,
	       'PERMA_LINK'    =>$perma_link, 
	       'PAGINATED'     =>!empty($pagination['page_string']) ? true : false, 
	       'STRING'        =>$pagination['page_string']
	    ));
	    
	    get_comments_link($comments_allow, $comments, $id);
	    get_image_status($image, $id);
	    
	    $ft->assign('RETURN', '');
	    $ft->parse('MAIN', ".note_row");
	    
	}
	
	// Parsowanie elementów poza DYNAMIC BLOCK :: fixed
    $ft->parse('MAIN', "rows");
} else {
    
    // Obs³uga b³êdu, kiedy w bazie danych nie ma jeszcze ¿adnego wpisu
    $ft->assign(array(
        'QUERY_FAILED'  =>$i18n['main_view'][1],
        'STRING'        =>'', 
        'PAGINATED'     =>false, 
        'MOVE_BACK'     =>false, 
        'MOVE_FORWARD'  =>false
    ));
    
    $ft->parse('MAIN', ".query_failed");
}

?>
