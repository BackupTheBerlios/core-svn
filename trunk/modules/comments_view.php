<?php

if(is_numeric($_GET['id'])) {

    $query = sprintf("
        SELECT * FROM
            %1\$s
        WHERE
            id = '%2\$d'
        LIMIT 1",

        $mysql_data['db_table'],
        $_GET['id']
    );

    $db->query($query);

    if($db->num_rows() > 0) {

        $page_string = empty($page_string) ? '' : $page_string;

        // Wy¶wietlamy tytu³, którego dotyczyæ bêdzie komentarz
        while($db->next_record()) {

            $id             = $db->f('id');
            $title          = $db->f('title');
            $comments_id    = $db->f('id');
            
            $perma_link  = isset($rewrite) && $rewrite == 1 ? '1,' . $id . ',1,item.html' : 'index.php?p=1&amp;id=' . $id . '';
            $submit_link = isset($rewrite) && $rewrite == 1 ? '1,' . $id . ',3,item.html' : 'index.php?p=3&amp;id=' . $id . '';

            $ft->assign(array(
                'NEWS_TITLE'    =>$title,
                'NEWS_ID'       =>$id,
                'COMMENTS_ID'   =>$id,
                'STRING'        =>$page_string, 
                'PERMA_LINK'    =>$perma_link, 
                'SUBMIT_LINK'   =>$submit_link
            ));

            $query = sprintf("
                SELECT * FROM
                    %1\$s
                WHERE
                    comments_id = '%2\$s'
                ORDER BY
                    date
                ASC",

                $mysql_data['db_table_comments'],
                $_GET['id']
            );

            $db->query($query);

            // Wy¶wietlamy komentarze do konkretnego wpisu
            while($db->next_record()) {

                $date           = $db->f('date');
                $text           = $db->f('text');
                $author         = $db->f('author');
                $comments_id    = $db->f('comments_id');
                $email          = $db->f('email');
                $id             = $db->f('id');

                // konwersja daty na bardziej ludzki format
                $date            = coreDateConvert($date);

                // przeszukanie text w poszukiwaniu ci±gów http, mail, ftp
                // zamiana ich na format linków
                $text            = coreMakeClickable($text);

                $search = array("'\[quote\]'si",
                                "'\[\/quote\]'si");

                $replace= array("<div class=\"quote\">",
                                "</div>");

                $text = preg_replace($search, $replace, $text);
                
                $quote_link = isset($rewrite) && $rewrite == 1 ? '1,' . $comments_id . ',3,' . $id . ',1,quote.html' : 'index.php?p=3&amp;id=' . $comments_id . '&amp;c=' . $id . '';

                $ft->assign(array(
                    'DATE'              =>$date,
                    'COMMENTS_TEXT'     =>$text,
                    'COMMENTS_AUTHOR'   =>$author,
                    'COMMENTS_ID'       =>$comments_id,
                    'AUTHOR_EMAIL'      =>$email,
                    'STRING'            =>$page_string,
                    'ID'                =>$id, 
                    'QUOTE_LINK'        =>$quote_link
                ));
                
                $ft->define("comments_view", "comments_view.tpl");
                $ft->define_dynamic("comments_row", "comments_view");

                $ft->parse('ROWS',	".comments_row");
            }
        }
        
        // Parsowanie szablonu comments_view.tpl
        $ft->parse('ROWS', "comments_view");
        
    } else {

        // Obs³uga b³êdu, kiedy u¿ytkownik próbuje kombinowaæ ze zmiennymi przechwytywanymi przez $_GET
        $ft->assign(array(
            'QUERY_FAILED'  =>$i18n['comments_view'][0],
            'STRING'        =>''
        ));

        $ft->parse('ROWS','.query_failed');
    }
} else {

    // Obs³uga b³êdu, kiedy u¿ytkownik próbuje kombinowaæ ze zmiennymi przechwytywanymi przez $_GET
    $ft->assign(array(
        'QUERY_FAILED'  =>$i18n['comments_view'][1],
        'STRING'        =>''
    ));

    $ft->parse('ROWS', '.query_failed');
}

?>