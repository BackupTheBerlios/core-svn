<?php

if(is_numeric($id)) {

    $cat_pagination_link = (bool)$rewrite ? 'category.' . $id . '.' : 'index.php?p=4&id=' . $id . '&amp;start=';
    
    // zliczamy liczbe postow na strone
    $query = sprintf("
        SELECT 
            category_post_perpage 
        FROM 
            %1\$s 
        WHERE 
            category_id = '%2\$d'", 
    
        TABLE_CATEGORY, 
        $id
    );
        
    $db->query($query);
    $db->next_record();
    
    $mainposts_per_page = $db->f('category_post_perpage');
    
    // zliczamy liczbe rekordow
    $query = sprintf("
        SELECT 
            COUNT(*) AS id 
        FROM 
            %1\$s a 
        LEFT JOIN 
            %2\$s b 
        ON 
            a.id = b.news_id 
        WHERE 
            b.category_id = %3\$d 
        AND 
            published = 1 
        ORDER BY 
            date", 
	
        TABLE_MAIN, 
        TABLE_ASSIGN2CAT, 
        $id
    );
    
    $db->query($query);
	$db->next_record();
	
	$num_items = $db->f("0");

    // inicjowanie funkcji stronnicujacej wpisy
    $pagination = pagination($cat_pagination_link, $mainposts_per_page, $num_items);

    // pobieramy nazwê szablonu jaki przydzielony jest do danej kategorii
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
            %3\$s c 
        ON 
            a.id = c.comments_id 
        LEFT JOIN 
            %4\$s d 
        ON 
            a.id = d.news_id 
        WHERE 
            d.category_id='%5\$d' 
        AND 
            b.category_id='%5\$d' 
        AND 
            published = '1' 
        AND 
            only_in_category IN(1, -1) 
        GROUP BY 
            a.date 
        DESC
        LIMIT  %6\$d, %7\$d",
        
        TABLE_MAIN,
        TABLE_CATEGORY,
        TABLE_COMMENTS, 
        TABLE_ASSIGN2CAT, 
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
        $ft->define_dynamic("cat_row", $category_tpl);

        while($db->next_record()) {
    
            $date           = date($date_format, $db->f('date'));
            $title          = $db->f('title');
            $text           = $db->f('text');
            $author         = $db->f('author');
            $id             = $db->f('id');
            $image          = $db->f('image');
            $comments_allow = $db->f('comments_allow');

            $comments       = $db->f('comments');
            
            list_assigned_categories($id);
            $perma_link = (bool)$rewrite ? sprintf('1,%s,1,item.html', $id) : 'index.php?p=1&amp;id=' . $id;
            
            $text   = highlighter($text, '<code>', '</code>');
            $text   = show_me_more($text);
            
            $ft->assign(array(
                'DATE'              =>$date,
                'NEWS_TITLE'        =>$title,
                'NEWS_TEXT'         =>$text,
                'NEWS_AUTHOR'       =>$author,
                'NEWS_ID'           =>$id,
                'PERMA_LINK'        =>$perma_link, 
                'PAGINATED'         =>!empty($pagination['page_string']) ? true : false, 
                'STRING'            =>$pagination['page_string']
            ));

            get_comments_link($comments_allow, $comments, $id);
            get_image_status($image, $id);
            
            $ft->assign('RETURN', '');
            $ft->parse('MAIN', ".note_row");
        }
        
        // Parsowanie szablonu przydzielonego do danej kategorii
        $ft->parse('MAIN', $category_tpl);
    } else {
        
        // Obs³uga b³êdu, kiedy ¿adana jest kategoria, jakiej nie ma w bazie danych
        $ft->assign(array(
            'QUERY_FAILED'  =>$i18n['category_view'][2],
            'STRING'        =>''
        ));
                            
        $ft->parse('MAIN', '.query_failed');
    }                    
} else {
    
    // Obs³uga b³êdu, kiedy u¿ytkownik próbuje kombinowaæ ze zmiennymi przechwytywanymi przez $_GET
    $ft->assign(array(
        'QUERY_FAILED'  =>$i18n['category_view'][3],
        'STRING'        =>''
    ));
                        
    $ft->parse('MAIN', '.query_failed');
}

?>