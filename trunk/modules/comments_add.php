<?php

// dekalracja zmiennej $page_string
$page_string        = empty($page_string) ? '' : $page_string;
$comment_author     = empty($_COOKIE['devlog_comment_user']) ? '' : $_COOKIE['devlog_comment_user'];

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {

    case 'add':

        $monit = array();

        // Obs³uga formularza, jesli go zatwierdzono
        if(!eregi('^([^0-9]+){2,}$', $_POST['author'])) {
            
            $monit[] = $i18n['comments_add'][0];
        }

        if($_POST['email'] != '') {

            // Sprawdzenie poprawnosci adresu e-mail
            if(!check_mail($_POST['email'])) {
                
                $monit[] = $i18n['comments_add'][1];
            }
        }

        // Je¿eli dane spe³niaja wszystkie kryteria dodanie nowego komentarza
        if(empty($monit)) {

            $tmp  = time();
            $date = date('Y-m-d H:i:s', $tmp);

            $text = str_nl2br($_POST['text']);

            // [b] i [/b] dla tekstu pogrubionego.
            $text = preg_replace('/\[b\]([^\"]+)\[\/b\]/','<b>\\1</b>', $text);

            // [i] i [/i] dla tekstu pochylonego.
            $text = preg_replace('/\[i\]([^\"]+)\[\/i\]/','<i>\\1</i>', $text);

            // [u] i [/u] dla tekstu podkre¶lonego.
            $text = preg_replace('/\[u\]([^\"]+)\[\/u\]/','<u>\\1</u>', $text);

            // [abbr] i [/abbr] dla akronimów.
            $text = preg_replace('/\[abbr=([^\"]+)\]([^\"]+)\[\/abbr\]/','<abbr title="\\1">\\2</abbr>', $text);
            
            // [link] i [/link] dla odsy³aczy.
            $text = preg_replace('/\[link\]([^\"]+)\[\/link\]/','<a href="\\1" target="_blank">\\1</a>', $text);

            // [link=] i [/link] dla odsy³aczy.
            $text = preg_replace('/\[link=([^\"]+)\]([^\"]+)\[\/link\]/','<a href="\\1" target="_blank">\\2</a>', $text);

            /*
             *czy preg_match_all i for ponizej sa potrzebne?
             *sam str_replace powinien wymienic wszystkie te stringi na ich odpowiedniki...
             *
             */
            $match_count = preg_match_all('#\[quote\](.*?)\[/quote\]#si', $text, $matches);

            for ($i = 0; $i < $match_count; $i++) {

                $text = str_replace(array('[quote]', '[/quote]'), array('<div class="quote">', '</div>'), $text);
            }

            $id             = $_POST['id'];
            $comments_id    = $_POST['comments_id'];
            $author         = $_POST['author'];
            $email          = $_POST['email'];
            $author_ip      = $_SERVER['REMOTE_ADDR'];

            @setcookie('devlog_comment_user', $author, time()+3600*8760);

            // egzemplarz klasy ³aduj±cej komentarz do bazy danych
            $query = sprintf("
                INSERT INTO
                    %s
                VALUES
                    ('','%s','%d','%s','%s','%s','%s')",

                $mysql_data['db_table_comments'],
                $date,
                $comments_id,
                $author,
                $author_ip,
                $email,
                $text
            );

            $db->query($query);

            // przydzielamy zmienne i parsujemy szablon
            $ft->assign(array(
                'NEWS_ID'       =>$_POST['id'],
                'STRING'        =>$page_string,
                'CONFIRMATION'  =>$i18n['comments_add'][2]
            ));

            $ft->parse('ROWS','.comments_submit');
        } else {
            
            $ft->define("error_reporting", "error_reporting.tpl");
            $ft->define_dynamic("error_row", "error_reporting");

            foreach ($monit as $error) {
    
                $ft->assign('ERROR_MONIT', $error);
                    
                $ft->parse('ROWS',	".error_row");
            }
                        
            $ft->parse('ROWS', "error_reporting");
        }
        break;

    default:

        // Je¶li wpis jest cytowany::request
        if(isset($_GET['c'])) {

            // Pobieramy tekst cytowanego wpisu::db
            $query = sprintf("
                SELECT
                    text
                FROM
                    %s
                WHERE
                    id = '%d'
                LIMIT 1",

                $mysql_data['db_table_comments'],
                $_GET['c']
             );

            $db->query($query);
            $db->next_record();

            // przypisanie zmiennych
            $cite   = $db->f('text');
            $author = $db->f('author');

            $match_count = preg_match_all('#\<div class="quote"\>(.*?)\</div\>#si', $cite, $matches);

            for ($i = 0; $i < $match_count; $i++) {

                $cite = str_replace(array('<div class="quote">', '</div>'), array('[quote]', '[/quote]'), $cite);
            }

            // Pobieramy id i tytu³ wpisu jakiego dotyczy komentarz::db
            $query = sprintf("
                SELECT
                    id, title
                FROM
                    %s
                WHERE
                    id = '%d'
                LIMIT 1",

                $mysql_data['db_table'],
                $_GET['id']
            );

            $db->query($query);
            $db->next_record();

            // przypisanie zmiennych
            $title  = $db->f('title');
            $id     = $db->f('id');

            // przypisanie tablicy szablonów::ft
            $ft->assign(array(
                'NEWS_TITLE'        =>$title,
                'NEWS_ID'           =>$id,
                'COMMENT_AUTHOR'    =>$comment_author,
                'QUOTE'             =>'[quote]' . strip_tags(br2nl($cite)) . '[/quote]',
                'STRING'            =>$page_string
            ));
        } else {

            $query = sprintf("
                SELECT
                    id, title
                FROM
                    %s
                WHERE
                    id = '%d'
                    LIMIT 1",

                $mysql_data['db_table'], 
                $_GET['id']
            );

            $db->query($query);
            $db->next_record();

            // przypisanie zmiennych
            $title  = $db->f('title');
            $id     = $db->f('id');

            // przypisanie tablicy szablonów::ft
            $ft->assign(array(
                'NEWS_TITLE'        =>$title,
                'NEWS_ID'           =>$id,
                'QUOTE'             =>'',
                'COMMENT_AUTHOR'    =>$comment_author,
                'STRING'            =>$page_string
            ));
        }

        // parsowanie szablonu::ft
        $ft->parse('ROWS','.comments_form');
        break;
}

?>