<?php
// $Id$

$comment_author = empty($_COOKIE['devlog_comment_user']) ? '' : $_COOKIE['devlog_comment_user'];
$action         = empty($_GET['action']) ? '' : $_GET['action'];

switch($action) {

    case 'add':

        $monit = array();

        if(!eregi('^([^0-9]+){2,}$', $_POST['author'])) {
            
            $monit[] = $i18n['comments_add'][0];
        }

        // E-mail check
        if(!empty($_POST['email']) && !check_mail($_POST['email'])) {
            
            $monit[] = $i18n['comments_add'][1];
        }

        // No errors - insert comment
        if(empty($monit)) {

            $text = str_nl2br($_POST['text']);
            
            $text = preg_replace('/\[b\]([^\"]+)\[\/b\]/','<b>\\1</b>', $text); // [b] / [/b]
            $text = preg_replace('/\[i\]([^\"]+)\[\/i\]/','<i>\\1</i>', $text); // [i] / [/i]
            $text = preg_replace('/\[u\]([^\"]+)\[\/u\]/','<u>\\1</u>', $text); // [u] / [/u]
            
            $text = preg_replace('/\[abbr=([^\"]+)\]([^\"]+)\[\/abbr\]/','<abbr title="\\1">\\2</abbr>', $text); // [abbr] / [/abbr]
            $text = preg_replace('/\[link\]([^\"]+)\[\/link\]/','<a href="\\1" target="_blank">\\1</a>', $text); // [link] / [/link]
            $text = preg_replace('/\[link=([^\"]+)\]([^\"]+)\[\/link\]/','<a href="\\1" target="_blank">\\2</a>', $text); // [link=] / [/link]

            $text = str_replace(array('[quote]', '[/quote]'), array('<div class="quote">', '</div>'), $text); 

            @setcookie('devlog_comment_user', $_POST['author'], time()+3600*8760);

            $query = sprintf("
                INSERT INTO
                    %s
                VALUES
                    ('',NOW(),'%d','%s','%s','%s','%s')",

                TABLE_COMMENTS,
                $_POST['comments_id'],
                $_POST['author'],
                $_SERVER['REMOTE_ADDR'],
                $_POST['email'],
                $text
            );

            $db->query($query);

            // przydzielamy zmienne i parsujemy szablon
            $ft->assign(array(
                'NEWS_ID'       =>$_POST['id'],
                'STRING'        =>'',
                'CONFIRMATION'  =>$i18n['comments_add'][2], 
                'SUBMIT_LINK'   =>showcomments_link($rewrite, $_POST['id'])
            ));

            $ft->assign('SHOW_COMMENT_FORM', false);
            $ft->define('comments_request', 'comments_request.tpl');
            
            // template parse::ft
            $ft->parse('MAIN','.comments_request');
        } else {
            
            $ft->define("error_reporting", "error_reporting.tpl");
            $ft->define_dynamic("error_row", "error_reporting");

            foreach ($monit as $error) {
    
                $ft->assign('ERROR_MONIT', $error);
                $ft->parse('MAIN',	".error_row");
            }
                        
            $ft->parse('MAIN', "error_reporting");
        }
        break;

    default:

        // Comment quoted::request
        if(isset($_GET['c'])) {

            // Quoted text::db
            $query = sprintf("
                SELECT
                    text
                FROM
                    %s
                WHERE
                    id = '%d'
                LIMIT 1",

                TABLE_COMMENTS,
                $_GET['c']
             );

            $db->query($query);
            $db->next_record();

            $cite   = $db->f('text');
            $author = $db->f('author');

            $cite = str_replace(array('<div class="quote">', '</div>'), array('[quote]', '[/quote]'), $cite);

            // Pobieramy id i tytu³ wpisu jakiego dotyczy komentarz::db
            $query = sprintf("
                SELECT
                    *
                FROM
                    %s
                WHERE
                    id = '%d'
                LIMIT 1",

                TABLE_MAIN,
                $_GET['id']
            );

            $db->query($query);
            $db->next_record();

            $id     = $db->f('id');
            $title  = $db->f('title');
            $text   = $db->f('text');
            $author = $db->f('author');

            // przypisanie tablicy szablonów::ft
            $ft->assign(array(
                'NEWS_TITLE'        =>$title,
                'NEWS_ID'           =>$id, 
                'NEWS_TEXT'         =>$text, 
                'NEWS_AUTHOR'       =>$author, 
                'COMMENT_AUTHOR'    =>$comment_author,
                'QUOTE'             =>sprintf('[quote]%s[/quote]', strip_tags(str_br2nl($cite))),
                'STRING'            =>'',
                'PERMA_LINK'        =>perma_link($rewrite, $id),
                'FORM_LINK'         =>form_link($rewrite)
            ));
        } else {

            $query = sprintf("
                SELECT
                    *
                FROM
                    %s
                WHERE
                    id = '%d'
                    LIMIT 1",

                TABLE_MAIN, 
                $_GET['id']
            );

            $db->query($query);
            $db->next_record();

            $id     = $db->f('id');
            $title  = $db->f('title');
            $text   = $db->f('text');
            $author = $db->f('author');

            // przypisanie tablicy szablonów::ft
            $ft->assign(array(
                'NEWS_TITLE'        =>$title,
                'NEWS_ID'           =>$id, 
                'NEWS_TEXT'         =>$text, 
                'NEWS_AUTHOR'       =>$author, 
                'QUOTE'             =>'',
                'COMMENT_AUTHOR'    =>$comment_author,
                'STRING'            =>'', 
                'PERMA_LINK'        =>perma_link($rewrite, $id), 
                'FORM_LINK'         =>form_link($rewrite)
            ));
        }
        
        $ft->assign('SHOW_COMMENT_FORM', true);
        $ft->define('comments_request', 'comments_request.tpl');
        
        // parsowanie szablonu::ft
        $ft->parse('MAIN','.comments_request');
        break;
}

$ft->assign(array(
    'STRING'        =>'', 
    'PAGINATED'     =>false, 
    'MOVE_BACK'     =>false, 
    'MOVE_FORWARD'  =>false
));

?>