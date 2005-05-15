<?php

$query = sprintf("
    SELECT * FROM 
        %1\$s 
    WHERE 
        id = '%2\$d' 
    AND 
        published = 'Y' 
    LIMIT 1", 

    TABLE_PAGES, 
    $_GET['id']
);

$db->query($query);

if($db->num_rows() !== 0) {
    
    $db->next_record();
    
    $title          = $db->f("title");
    $text           = $db->f("text");
    $id             = $db->f("id");
    $image          = $db->f("image");
    $assigned_tpl   = $db->f("assigned_tpl");
    
    // dynamiczne definiowanie szablonu, jaki ma byc
    // przydzielony do konkretnej podstrony Core
    $ft->define($assigned_tpl, $assigned_tpl . '_page.tpl');
    
    $ft->assign(array(
        'PAGE_TITLE'    =>$title,
        'PAGE_TEXT'     =>$text,
        'PAGE_ID'       =>$id
    ));
    
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