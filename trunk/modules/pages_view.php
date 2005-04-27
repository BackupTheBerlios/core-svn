<?php

$query = sprintf("
    SELECT * FROM 
        %1\$s 
    WHERE 
        id = '%2\$d' 
    AND 
        published = 'Y' 
    LIMIT 1", 

    $mysql_data['db_table_pages'], 
    $_GET['id']
);

$db->query($query);

if($db->num_rows() !== 0) {
    
    $db->next_record();
    
    $title  = $db->f("title");
    $text   = $db->f("text");
    $id     = $db->f("id");
    $image  = $db->f("image");
    
    $ft->assign(array(
        'PAGE_TITLE'    =>$title,
        'PAGE_TEXT'     =>$text,
        'PAGE_ID'       =>$id
    ));
    
    if(empty($image)) {

		$ft->assign(array('IMAGE' =>""));
	} else {
	    
	    $img_path = get_root() . '/photos/' . $image;
		
		if(is_file($img_path)) {
			
			list($width, $height) = getimagesize($img_path);
		
			// wysoko¶æ, szeroko¶æ obrazka
			$ft->assign(array(
                'WIDTH'		=>$width,
                'HEIGHT'	=>$height
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
	
	// template prepare
	$ft->define('pages_view', "pages_view.tpl");

	$ft->parse('ROWS',".pages_view");
} else {
	
	$ft->assign(array(
        'QUERY_FAILED'  =>$i18n['pages_view'][0],
        'STRING'        =>""
    ));
	
	$ft->parse('ROWS', ".query_failed");
}

?>