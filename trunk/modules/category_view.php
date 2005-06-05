<?php

if(is_numeric($id)) {

    if ((bool)$rewrite) {
        $cat_pagination_link = 'category.' . $id . '.';
    } else {
        $cat_pagination_link = 'index.php?p=4&id=' . $id . '&amp;start=';
    }

    // inicjowanie funkcji stronnicujacej wpisy
    main_pagination($cat_pagination_link, 'WHERE c_id=' . $id . ' AND ', 'mainposts_per_page', 'published = \'1\'', TABLE_MAIN, true);

    // pobieramy nazw� szablonu jaki przydzielony jest do danej kategorii
    $query = sprintf("
        SELECT 
            category_tpl 
        FROM 
            %1\$s 
        WHERE 
            category_id = '%2\$d' 
        LIMIT 1", 

        TABLE_CATEGORY, 
        $id
    );

    $db->query($query);
    $db->next_record();

    // zmienna przechowujaca przydzielony do kategorii szablon
    $category_tpl = $db->f('category_tpl') . '_rows';

    $query = sprintf("
        SELECT 
            a.*,
            UNIX_TIMESTAMP(a.date) AS date,
            b.*,
            c.comments_id,
            count(c.id) AS comments 
        FROM 
            %1\$s a,
            %2\$s b
        LEFT JOIN 
            %3\$s  c 
        ON 
            a.id = c.comments_id
        WHERE 
            a.c_id='%4\$d' 
        AND 
            b.category_id='%4\$d' 
        AND 
            published = '1' 
        AND 
            only_in_category IN(1, -1) 
        GROUP BY 
            a.date 
        DESC
        LIMIT  %5\$d, %6\$d",
        
        TABLE_MAIN,
        TABLE_CATEGORY,
        TABLE_COMMENTS,
        $id, 
        $start, 
        $mainposts_per_page
    );
    
    $db->query($query);
    
    if($db->num_rows() > 0) {
        
        // zabezpieczenie, jesli plik nie znajduje sie na serwerze
        $category_tpl = file_exists('./templates/' . $theme . '/tpl/' . $category_tpl . '.tpl') ? $category_tpl : 'default_rows';
        
        // dynamiczne definiowanie szablonu, jaki ma byc
        // przydzielony do konkretnej kategorii Core
        $ft->define($category_tpl, $category_tpl . '.tpl');
            
        // definiujemy blok dynamiczny szablonu
        $ft->define_dynamic("note_row", $category_tpl);

        while($db->next_record()) {
    
            $date           = date($date_format, $db->f('date'));
            $title          = $db->f('title');
            $text           = $db->f('text');
            $author         = $db->f('author');
            $id             = $db->f('id');
            $c_id           = $db->f('c_id');
            $image          = $db->f('image');
            $comments_allow = $db->f('comments_allow');
    
            $c_name         = $db->f('category_name');
            $c_id           = $db->f('category_id');

            $comments       = $db->f('comments');
    
            if ((bool)$rewrite) {
                $perma_link    = '1,' . $id . ',1,item.html';
                $category_link = '1,' . $c_id . ',4,item.html';
            } else {
                $perma_link    = 'index.php?p=1&amp;id=' . $id . '';
                $category_link = 'index.php?p=4&amp;id=' . $c_id . '';
            }
            
            $text   = highlighter($text, '<code>', '</code>');
            $text   = show_me_more($text);
            
            $ft->assign(array(
                'DATE'              =>$date,
                'NEWS_TITLE'        =>$title,
                'NEWS_TEXT'         =>$text,
                'NEWS_AUTHOR'       =>$author,
                'NEWS_ID'           =>$id,
                'CATEGORY_NAME'     =>$c_name, 
                'SELECTED_CATEGORY' =>$c_name, 
                'NEWS_CATEGORY'     =>$c_id, 
                'PERMA_LINK'        =>$perma_link,
                'CATEGORY_LINK'     =>$category_link
            ));
                        
            if($page_string) {
        
                $ft->assign('STRING', $i18n['category_view'][0] . $page_string);
            } else {
        
                $ft->assign('STRING', $page_string);
            }

            get_comments_link($comments_allow, $comments, $id);
            get_image_status($image, $id);
            
            $ft->assign('RETURN', '');
            $ft->parse('MAIN', ".note_row");
        }
        
        // Parsowanie szablonu przydzielonego do danej kategorii
        $ft->parse('MAIN', $category_tpl);
    } else {
        
        // Obs�uga b��du, kiedy �adana jest kategoria, jakiej nie ma w bazie danych
        $ft->assign(array(
            'QUERY_FAILED'  =>$i18n['category_view'][2],
            'STRING'        =>''
        ));
                            
        $ft->parse('MAIN', '.query_failed');
    }                    
} else {
    
    // Obs�uga b��du, kiedy u�ytkownik pr�buje kombinowa� ze zmiennymi przechwytywanymi przez $_GET
    $ft->assign(array(
        'QUERY_FAILED'  =>$i18n['category_view'][3],
        'STRING'        =>''
    ));
                        
    $ft->parse('MAIN', '.query_failed');
}

?>
