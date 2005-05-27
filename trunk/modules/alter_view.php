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
    
    $perma_link    = isset($rewrite) && $rewrite == 1 ? '1,' . $id . ',1,item.html' : 'index.php?p=1&amp;id=' . $id . '';
	$category_link = isset($rewrite) && $rewrite == 1 ? '1,' . $c_id . ',4,item.html' : 'index.php?p=4&amp;id=' . $c_id . '';
	
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

    if(($comments_allow) == 0 ) {

        $ft->assign(array(
            'COMMENTS_ALLOW'    =>false, 
            'COMMENTS'          =>''
        ));
    } else {
	        
        if($comments == 0) {
	            
            $comments_link = isset($rewrite) && $rewrite == 1 ? '1,' . $id . ',3,item.html' : 'index.php?p=3&amp;id=' . $id . '';
            $ft->assign(array(
                'COMMENTS_LINK' =>$comments_link, 
                'COMMENTS_ALLOW'=>true, 
                'COMMENTS'      =>''
            ));
	    } else {
	            
            $comments_link = isset($rewrite) && $rewrite == 1 ? '1,' . $id . ',2,item.html' : 'index.php?p=2&amp;id=' . $id . '';
            $ft->assign(array(
                'COMMENTS_LINK' =>$comments_link, 
                'COMMENTS_ALLOW'=>true, 
                'COMMENTS'      =>$comments
            ));
	    }
    }

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
            
            $photo_link = isset($rewrite) && $rewrite == 1 ? 'photo?id=' . $id . '' : 'photo.php?id=' . $id . '';
            
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
    
    // definiujemy blok dynamiczny szablonu
    $ft->define_dynamic("note_row", "rows");
    
    $ft->assign('RETURN', 'powrót');
    $ft->parse('MAIN','.note_row');
} else {

    $ft->assign(array(
        'QUERY_FAILED'  =>$i18n['alter_view'][0],
        'STRING'        =>''
    ));

    $ft->parse('MAIN','.query_failed');
}

?>