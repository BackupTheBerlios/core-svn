<?php
// $Id$

$query = sprintf("
    SELECT
        a.*, 
        UNIX_TIMESTAMP(a.date) AS date, 
        c.comments_id,
        count(c.id)
    AS
       comments
    FROM
        %1\$s a 
    LEFT JOIN 
        %3\$s c 
    ON 
        a.id = c.comments_id
    WHERE
        a.id = '%4\$d'
    AND
        published = '1'
    GROUP BY
        a.date
    DESC
    LIMIT 1",

    TABLE_MAIN,
    TABLE_CATEGORY,
    TABLE_COMMENTS,
    $_GET['id']
);

$db->query($query);

if($db->num_rows() > 0) {

    $db->next_record();

    $date           = date($date_format, $db->f("date"));
    $title          = $db->f('title');
    $text           = str_replace(array('[podziel]', '[more]'), '', $db->f('text'));
    $author         = $db->f('author');
    $id             = $db->f('id');
    $image          = $db->f('image');
    $comments_allow = $db->f('comments_allow');

    // Przypisanie zmiennej $comments
    $comments       = $db->f('comments');
    
    $ft->define_dynamic("cat_row", "rows");
    
    list_assigned_categories($id);
	
	$text = preg_replace("/\[code:\"?([a-zA-Z0-9\-_\+\#\$\%]+)\"?\](.*?)\[\/code\]/sie", "highlighter('\\2', '\\1')", $text);

    $ft->assign(array(
        'DATE'          =>$date,
        'NEWS_TITLE'    =>$title,
        'NEWS_TEXT'     =>$text,
        'NEWS_AUTHOR'   =>$author,
        'NEWS_ID'       =>$id,
        'STRING'        =>'', 
        'PERMA_LINK'    =>perma_link($rewrite, $id), 
        'PAGINATED'     =>false, 
        'MOVE_BACK'     =>false, 
        'MOVE_FORWARD'  =>false
    ));

    get_comments_link($comments_allow, $comments, $id);
    get_image_status($image, $id);
    
    // definiujemy blok dynamiczny szablonu
    $ft->define_dynamic("note_row", "rows");
    
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
