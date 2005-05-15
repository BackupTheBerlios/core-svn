<?php

$pagination_link = isset($rewrite) && $rewrite == 1 ? 'index.' : 'index.php?start=';

// inicjowanie funkcji stronnicuj±cej wpisy
$pagination = main_pagination($pagination_link, '', 'mainposts_per_page', 'WHERE published = \'1\'', TABLE_MAIN);

$query = sprintf("
	SELECT 
        a.*,
        UNIX_TIMESTAMP(a.date) AS date,
		b.*,
		c.comments_id,
		count(c.id) AS comments 
	FROM 
		%1\$s a 
	LEFT JOIN 
		%2\$s b 
	ON 
		b.category_id = a.c_id 
	LEFT JOIN 
		%3\$s c 
	ON 
		a.id = c.comments_id
	WHERE 
		published = '1' 
	GROUP BY 
		a.date 
	DESC 
	LIMIT $start, $pagination[mainposts_per_page]", 
    
    TABLE_MAIN, 
    TABLE_CATEGORY, 
    TABLE_COMMENTS
);

$db->query($query);

// Sprawdzamy, czy w bazie danych s± ju¿ jakie¶ wpisy
if($db->num_rows() !== 0) {

	while($db->next_record()) {
	    
	    $date              = date($date_format, $db->f("date"));
	    $title             = $db->f("title");
	    $text              = $db->f("text");
	    $author            = $db->f("author");
	    $id                = $db->f("id");
	    $c_id              = $db->f("c_id");
	    $image             = $db->f("image");
	    $comments_allow    = $db->f("comments_allow");
	    
	    $c_id              = $db->f("category_id");
	    
	    $comments          = $db->f("comments");
	    
	    $c_name            = str_replace('&', '&amp;', $db->f('category_name'));
	    
        if(isset($rewrite) && $rewrite == 1) {
            
            $perma_link     = sprintf('1,%s,1,item.html', $id);
            $category_link  = sprintf('1,%s,4,item.html', $c_id);
        } else {
            
            $perma_link = 'index.php?p=1&amp;id=' . $id;
            $category_link = 'index.php?p=4&amp;id=' . $c_id;
        }
	    
	    $text = show_me_more($text);
	    
	    $ft->assign(array(
	       'DATE'          =>$date,
	       'NEWS_TITLE'    =>$title,
	       'NEWS_TEXT'     =>$text,
	       'NEWS_AUTHOR'   =>$author,
	       'NEWS_ID'       =>$id,
	       'CATEGORY_NAME' =>$c_name,
	       'NEWS_CATEGORY' =>$c_id,
	       'PERMA_LINK'    =>$perma_link,
	       'CATEGORY_LINK' =>$category_link
	    ));
	    
	    if(!empty($pagination['page_string'])) {
	        
	        $ft->assign('STRING', "<b>Id¼ do strony:</b> " . $pagination['page_string']);
	    } else {
	        
	        $ft->assign('STRING', $pagination['page_string']);
	    }
	    
	    if(($comments_allow) == 0 ) {
	        
            /*
             * WYWALIC HTML
             *
             */
             
	        $ft->assign('COMMENTS_ALLOW', '<br />');
	    } else {
	        
	        // template prepare
	        $ft->define('comments_link', "comments_link.tpl");
	        
	        if($comments == 0) {
	            
	            $comments_link = isset($rewrite) && $rewrite == 1 ? '1,' . $id . ',3,item.html' : 'index.php?p=3&amp;id=' . $id . '';
	            $ft->assign(array(
                    'COMMENTS_LINK' =>$comments_link, 
                    'COMMENTS'      =>''
                ));
	        } else {
	            
	            $comments_link = isset($rewrite) && $rewrite == 1 ? '1,' . $id . ',2,item.html' : 'index.php?p=2&amp;id=' . $id . '';
	            $ft->assign(array(
                    'COMMENTS_LINK' =>$comments_link, 
                    'COMMENTS'      =>$comments
                ));
	        }
	        
	        // template parse
	        $ft->parse('COMMENTS_ALLOW', "comments_link");
	    }
	    
	    if(empty($image)) {
	        
	        $ft->assign('IMAGE', '');
	    
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
	            
	            // template prepare
	            $ft->define('image', "image.tpl");
	            
	            if($width > $max_photo_width) {
	                
	                $ft->assign(array(
                        'UID'           =>$id,
                        'IMAGE_NAME'    =>''
                    ));
	            } else {
	                $ft->assign('IMAGE_NAME', $image);
	            }
	            
	            $ft->parse('IMAGE', "image");
	        }
	    }
	    
	    // definiujemy blok dynamiczny szablonu
	    $ft->define_dynamic("note_row", "rows");
	    
	    $ft->assign('RETURN', '');
	    $ft->parse('ROWS', ".note_row");
	}
} else {
    
    // Obs³uga b³êdu, kiedy w bazie danych nie ma jeszcze ¿adnego wpisu
    $ft->assign(array(
        'QUERY_FAILED'  =>$i18n['main_view'][0],
        'STRING'        =>""
    ));
    
    $ft->parse('ROWS', ".query_failed");
}

?>