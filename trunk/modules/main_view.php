<?php

// inicjowanie funkcji stronnicuj�cej wpisy
$pagination = main_pagination('index.', '', 'mainposts_per_page', 'AND published = \'1\'', 'db_table');

$query = "
	SELECT 
		a.*,
		b.*,
		c.comments_id,
		count(c.id) AS comments 
	FROM 
		$mysql_data[db_table] a 
	LEFT JOIN 
		$mysql_data[db_table_category] b 
	ON 
		b.category_id = a.c_id 
	LEFT JOIN 
		$mysql_data[db_table_comments] c 
	ON 
		a.id = c.comments_id
	WHERE 
		published = '1' 
	GROUP BY 
		a.date 
	DESC 
	LIMIT $start, $pagination[mainposts_per_page]";

$db->query($query);

// Sprawdzamy, czy w bazie danych s� ju� jakie� wpisy
if($db->num_rows() !== 0) {

	while($db->next_record()) {
	    
	    $date              = $db->f("date");
	    $title             = $db->f("title");
	    $text              = $db->f("text");
	    $author            = $db->f("author");
	    $id                = $db->f("id");
	    $c_id              = $db->f("c_id");
	    $image             = $db->f("image");
	    $comments_allow    = $db->f("comments_allow");
	    
	    $c_id              = $db->f("category_id");
	    
	    $comments          = $db->f("comments");
	    
	    // konwersja daty na bardziej ludzki format
	    $date      = coreDateConvert($date);
	    
	    $c_name    = str_replace('&', '&amp;', $db->f('category_name'));
	    
	    $ft->assign(array(
	       'DATE'          =>$date,
	       'NEWS_TITLE'    =>stripslashes($title),
	       'NEWS_TEXT'     =>stripslashes($text),
	       'NEWS_AUTHOR'   =>$author,
	       'NEWS_ID'       =>$id,
	       'CATEGORY_NAME' =>$c_name,
	       'NEWS_CATEGORY' =>$c_id
	    ));
	    
	    if($pagination['page_string'] !== "") {
	        
	        $ft->assign('STRING', "<b>Id� do strony:</b> " . $pagination['page_string']);
	    } else {
	        
	        $ft->assign('STRING', $pagination['page_string']);
	    }
	    
	    if(($comments_allow) == 0 ) {
	        
	        $ft->assign('COMMENTS_ALLOW', '<br />');
	    } else {
	        
	        if($comments == 0) {
	            
	            // template prepare
	            $ft->define('comments_link_empty', "comments_link_empty.tpl");
	            
	            $ft->parse('COMMENTS_ALLOW', "comments_link_empty");
	        } else {
	            
	            // template prepare
	            $ft->define('comments_link_alter', "comments_link_alter.tpl");
	            $ft->assign('COMMENTS', $comments);
	            
	            $ft->parse('COMMENTS_ALLOW', "comments_link_alter");
	        }
	    }
	    
	    if(empty($image)) {
	        
	        $ft->assign('IMAGE', '');
	    
	    } else {
	        
	        $img_path = get_root() . '/photos/' . $image;
	        
	        if(is_file($img_path)) {
	            
	            list($width, $height) = getimagesize($img_path);
	            
	            // wysoko��, szeroko�� obrazka
	            $ft->assign(array(
                    'WIDTH'     =>$width,
                    'HEIGHT'    =>$height
	            ));
	            
	            if($width > $max_photo_width) {
	                
	                // template prepare
	                $ft->define('image_alter', "image_alter.tpl");
	                $ft->assign('UID', $id);
	                
	                $ft->parse('IMAGE', "image_alter");
	            
	            } else {
	                
	                // template prepare
	                $ft->define('image_main', "image_main.tpl");
	                $ft->assign('IMAGE_NAME', $image);
	                
	                $ft->parse('IMAGE', "image_main");
	            }
	        }
	    }
	    
	    $ft->parse('ROWS', ".rows");
	}
} else {
    
    // Obs�uga b��du, kiedy w bazie danych nie ma jeszcze �adnego wpisu
    $ft->assign(array(
        'QUERY_FAILED'  =>$i18n['main_view'][0],
        'STRING'        =>""
    ));
    
    $ft->parse('ROWS', ".query_failed");
}

?>