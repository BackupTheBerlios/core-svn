<?php
// $Id$

$date = explode('-', $_GET['date']);

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
        only_in_category = -1 
    AND 
        MONTH(a.date) = '%4\$d' 
    AND 
        DAYOFMONTH(a.date) = %5\$d 
	GROUP BY 
		a.date 
	DESC",
    
    TABLE_MAIN, 
    TABLE_CATEGORY, 
    TABLE_COMMENTS,
    $date[0], 
    $date[1]
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
        
        $text = preg_replace("/\[code:\"?([a-zA-Z0-9\-_\+\#\$\%]+)\"?\](.*?)\[\/code\]/sie", "highlighter('\\2', '\\1')", $text);
        $text   = show_me_more($text);
	    
	    $ft->assign(array(
            'DATE'          =>$date,
            'NEWS_TITLE'    =>$title,
            'NEWS_TEXT'     =>$text,
            'NEWS_AUTHOR'   =>$author,
            'NEWS_ID'       =>$id,
            'NEWS_CATEGORY' =>$c_id,
            'PERMA_LINK'    =>$perma_link, 
            'STRING'        =>'', 
            'PAGINATED'     =>false, 
            'MOVE_BACK'     =>false, 
            'MOVE_FORWARD'  =>false
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
        'QUERY_FAILED'  =>$i18n['date_view'][0],
        'STRING'        =>'', 
        'PAGINATED'     =>false, 
        'MOVE_BACK'     =>false, 
        'MOVE_FORWARD'  =>false
    ));
    
    $ft->parse('MAIN', ".query_failed");
}

?>
