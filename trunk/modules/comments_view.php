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

            $ft->assign(array(
                'NEWS_TITLE'    =>$title,
                'NEWS_ID'       =>$id,
                'COMMENTS_ID'   =>$id,
                'STRING'        =>$page_string,
                'COMMENTS_ADD'  =>'<a class="comments" href="1,' . $id . ',3,item.html">Dodaj komentarz</a>'
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

                $ft->assign(array(
                    'DATE'              =>$date,
                    'COMMENTS_TEXT'     =>$text,
                    'COMMENTS_AUTHOR'   =>$author,
                    'COMMENTS_ID'       =>$comments_id,
                    'AUTHOR_EMAIL'      =>$email,
                    'STRING'            =>$page_string,
                    'ID'                =>$id,

                    'COMMENTS_QUOTE'    =>'<a class="comments" href="1,' . $comments_id . ',3,' . $id . ',1,quote.html">odpowiedz cytuj±c</a>'
                ));

                $ft->parse('ROWS', '.comments_rows');
            }
        }
    } else {

        // Obs³uga b³êdu, kiedy u¿ytkownik próbuje kombinowaæ ze zmiennymi przechwytywanymi przez $_GET
        $ft->assign(array(
            'QUERY_FAILED'  =>'W bazie danych nie ma wpisu o ¿±danym id',
            'STRING'        =>''
        ));

        $ft->parse('ROWS','.query_failed');
    }
} else {

    // Obs³uga b³êdu, kiedy u¿ytkownik próbuje kombinowaæ ze zmiennymi przechwytywanymi przez $_GET
    $ft->assign(array(
        'QUERY_FAILED'  =>'Szukasz czego¶?',
        'STRING'        =>''
    ));

    $ft->parse('ROWS', '.query_failed');
}

?>