<?php

$query = sprintf("
    SELECT
        a.*, 
        UNIX_TIMESTAMP(a.date) AS date, 
        b.*,
        c.comments_id,
        count(c.id)
    AS
       comments
    FROM
        %s a,
        %s b
    LEFT JOIN
            %s c
        ON
            a.id = c.comments_id
    WHERE
        a.id = '%d'
    AND
        b.category_id = a.c_id
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
    $text           = str_replace('[podziel]', '', $db->f('text'));
    $author         = $db->f('author');
    $id             = $db->f('id');
    $c_id           = $db->f('c_id');
    $image          = $db->f('image');
    $comments_allow = $db->f('comments_allow');

    $c_id           = $db->f('category_id');

    $c_name         = str_replace('&', '&amp;', $db->f('category_name'));

    // Przypisanie zmiennej $comments
    $comments       = $db->f('comments');
    
    if ((bool)$rewrite) {
        $perma_link    = '1,' . $id . ',1,item.html';
    	$category_link = '1,' . $c_id . ',4,item.html';
    } else {
        $perma_link    = 'index.php?p=1&amp;id=' . $id;
    	$category_link = 'index.php?p=4&amp;id=' . $c_id;
    }
	
	$text   = highlighter($text, '<code>', '</code>');

    $ft->assign(array(
        'DATE'          =>$date,
        'NEWS_TITLE'    =>$title,
        'NEWS_TEXT'     =>$text,
        'NEWS_AUTHOR'   =>$author,
        'NEWS_ID'       =>$id,
        'CATEGORY_NAME' =>$c_name,
        'NEWS_CATEGORY' =>$c_id,
        'STRING'        =>'', 
        'PERMA_LINK'    =>$perma_link,
	    'CATEGORY_LINK' =>$category_link
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
        'STRING'        =>''
    ));

    $ft->parse('MAIN','.query_failed');
}

?>
