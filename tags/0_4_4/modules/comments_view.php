<?php

if(is_numeric($_GET['id'])) {

    $query = sprintf("
        SELECT * FROM
            %1\$s
        WHERE
            id = '%2\$d'
        LIMIT 1",

        TABLE_MAIN,
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
            
            if (isset($rewrite) && $rewrite == 1) {
              $perma_link = sprintf('1,%s,1,item.html', $id);
              $submit_link = sprintf('1,%s,3,item.html', $id);
            } else {
              $perma_link = 'index.php?p=1&amp;id=' . $id;
              $submit_link = 'index.php?p=3&amp;id=' . $id;
            }

            switch ($db->f('comments_allow')) {
              case 1:
                $show_addcomment = true;
              break;
              case -1:
                $show_addcomment = loggedIn();
              break;
              default:
                $show_addcomment = false;
            }
              

            $ft->assign(array(
                'NEWS_TITLE'      =>$title,
                'NEWS_ID'         =>$id,
                'COMMENTS_ID'     =>$id,
                'STRING'          =>$page_string, 
                'PERMA_LINK'      =>$perma_link, 
                'SUBMIT_LINK'     =>$submit_link,
                'SHOW_ADDCOMMENT' =>$show_addcomment,
            ));

            $query = sprintf("
                SELECT
                    id,
                    UNIX_TIMESTAMP(date) AS date,
                    comments_id,
                    author,
                    author_ip,
                    email,
                    text
                FROM
                    %1\$s
                WHERE
                    comments_id = '%2\$s'
                ORDER BY
                    date
                ASC",

                TABLE_COMMENTS,
                $_GET['id']
            );

            $db->query($query);

            // Wy¶wietlamy komentarze do konkretnego wpisu
            while($db->next_record()) {

                $date           = date($date_format, $db->f('date'));
                $text           = $db->f('text');
                $author         = $db->f('author');
                $comments_id    = $db->f('comments_id');
                $email          = $db->f('email');
                $id             = $db->f('id');

                // przeszukanie text w poszukiwaniu ci±gów http, mail, ftp
                // zamiana ich na format linków
                $text            = coreMakeClickable($text);

                $text = str_replace(array('[quote]', '[/quote]'), array('<div class="quote">', '</div>'), $text);
                
                if ((bool)$rewrite) {
                    $quote_link = sprintf('1,%s,3,%s,1,quote.html', $comments_id, $id);
                } else {
                    $quote_link = sprintf('index.php?p=3&amp;id=%s&amp;c=%s', $comments_id, $id);
                }

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

                $ft->parse('MAIN',	".comments_row");
            }
        }
        
        // Parsowanie szablonu comments_view.tpl
        $ft->parse('MAIN', "comments_view");
        
    } else {

        // Obs³uga b³êdu, kiedy u¿ytkownik próbuje kombinowaæ ze zmiennymi przechwytywanymi przez $_GET
        $ft->assign(array(
            'QUERY_FAILED'  =>$i18n['comments_view'][0],
            'STRING'        =>''
        ));

        $ft->parse('MAIN','.query_failed');
    }
} else {

    // Obs³uga b³êdu, kiedy u¿ytkownik próbuje kombinowaæ ze zmiennymi przechwytywanymi przez $_GET
    $ft->assign(array(
        'QUERY_FAILED'  =>$i18n['comments_view'][1],
        'STRING'        =>''
    ));

    $ft->parse('MAIN', '.query_failed');
}

$ft->assign(array(
    'STRING'          =>'', 
    'PAGINATED'       =>false, 
    'MOVE_BACK'       =>false, 
    'MOVE_FORWARD'    =>false,
));

?>
