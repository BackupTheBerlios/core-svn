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

    $mysql_data['db_table'],
    $mysql_data['db_table_category'],
    $mysql_data['db_table_comments'],
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

    if(!$comments_allow) {

        $ft->assign(array('COMMENTS_ALLOW' =>'<br />'));
    } else {

        if(!$comments) {
            
            $comments_link = isset($rewrite) && $rewrite == 1 ? '1,' . $id . ',3,item.html' : 'index.php?p=3&amp;id=' . $id . '';
	        $ft->assign('COMMENTS_LINK', $comments_link);

            // template prepare
            $ft->define('comments_link_empty', 'comments_link_empty.tpl');
            $ft->parse('COMMENTS_ALLOW', 'comments_link_empty');
        } else {
            
            $comments_link = isset($rewrite) && $rewrite == 1 ? '1,' . $id . ',2,item.html' : 'index.php?p=2&amp;id=' . $id . '';
	        $ft->assign('COMMENTS_LINK', $comments_link);

            // template prepare
            $ft->define('comments_link_alter', 'comments_link_alter.tpl');
            $ft->assign('COMMENTS', $comments);

            $ft->parse('COMMENTS_ALLOW', 'comments_link_alter');
        }
    }

    if(empty($image)) {

        $ft->assign(array('IMAGE' =>''));
    } else {

        $img_path = get_root() . '/photos/' . $image;

        if(is_file($img_path)) {
            list($width, $height) = getimagesize($img_path);
            
            $photo_link = isset($rewrite) && $rewrite == 1 ? 'photo?id=' . $id . '' : 'photo.php?id=' . $id . '';

            // wysoko¶æ, szeroko¶æ obrazka
            $ft->assign(array(
                'WIDTH'     =>$width,
                'HEIGHT'    =>$height, 
                'PHOTO_LINK'=>$photo_link
            ));

            if($width > $max_photo_width) {

                // template prepare
                $ft->define('image_alter', 'image_alter.tpl');
                $ft->assign('UID', $id);

                $ft->parse('IMAGE', 'image_alter');
            } else {

                // template prepare
                $ft->define('image_main', 'image_main.tpl');
                $ft->assign('IMAGE_NAME', $image);

                $ft->parse('IMAGE', 'image_main');
            }
        }
    }

    $ft->parse('ROWS','.single_rows');
} else {

    $ft->assign(array(
        'QUERY_FAILED'  =>$i18n['alter_view'][0],
        'STRING'        =>''
    ));

    $ft->parse('ROWS','.query_failed');
}

?>
