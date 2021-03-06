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
    $id
);

// template prepare
$ft->define('pages_view', "pages_view.tpl");

$db->query($query);

if($db->num_rows() !== 0) {
    
    $db->next_record();
    
    $title          = $db->f("title");
    $text           = $db->f("text");
    $id             = $db->f("id");
    $parent_id      = $db->f("parent_id");
    $image          = $db->f("image");
    $assigned_tpl   = $db->f("assigned_tpl");
    
    // dynamiczne definiowanie szablonu, jaki ma byc
    // przydzielony do konkretnej podstrony Core
    $ft->define($assigned_tpl, $assigned_tpl . '_page.tpl');
    
    $text = highlighter($text, '<code>', '</code>');
    
    $ft->assign(array(
        'PAGE_TITLE'    =>$title,
        'PAGE_TEXT'     =>$text,
        'PAGE_ID'       =>$id, 
        'PAGINATED'     =>false, 
        'MOVE_BACK'     =>false, 
        'MOVE_FORWARD'  =>false
    ));
    
    // Parsowanie nazw stron rodzicielskich::parent	
    $ft->define_dynamic("breadcrumb_row", "pages_view");
    
    // tablice przechowujace tytul i id strony
    $pages_sort[]   = $title;
    $pages_id[]     = $id;
    
    // funkcja pobierająca rekurencyjnie strony dziedziczące::child
    get_breadcrumb($parent_id, 2);
    
    function cmp($pages_sort, $b) {
        if ($pages_sort == $b) return 0;
        return ($pages_sort > $b) ? -1 : 1;
    }
    
    // sortujemy tablice w porzadku odwrotnym
    uksort($pages_sort, "cmp");
    uksort($pages_id, "cmp");

    // parsujemy menu na podstawie tablicy
    foreach ($pages_sort as $pid => $ptitle) {
    
        if ((bool)$rewrite) {
            $page_link  = sprintf('1,%s,5,item.html', $pages_id[$pid]);
        } else {
            $page_link  = 'index.php?p=5&amp;id=' . $pages_id[$pid];
        }
        $ft->assign(array(
            'PAGE_LINK'   =>$page_link, 
            'PAGE_TITLE'  =>$ptitle
        ));
    
        $ft->parse('BREADCRUMB_ROW', ".breadcrumb_row");
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
            
            if ((bool)$rewrite) {
                $photo_link = 'photo?p=5&amp;id=' . $id;
            } else {
                $photo_link = 'photo.php?p=5&amp;id=' . $id;
            }
            
            // wysokość, szerokość obrazka
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

	$ft->parse('MAIN', ".pages_view");
} else {
	
	$ft->assign(array(
        'QUERY_FAILED'  =>$i18n['pages_view'][0],
        'STRING'        =>'', 
        'PAGINATED'     =>false, 
        'MOVE_BACK'     =>false, 
        'MOVE_FORWARD'  =>false
    ));
	
	$ft->parse('MAIN', ".query_failed");
}

?>
