<?php

// deklaracja zmiennej $action::form
$action     = empty($_GET['action']) ? '' : $_GET['action'];

$monit = array();
$ft->assign('NOTE_PREVIEW', false);
if (isset($_POST['sub_commit'])) { //modyfikujemy wpis
    $title      = trim($_POST['title']);
    $date       = isset($_POST['now']) ? date('Y-m-d H:i:s') : $_POST['date'];
    $filename   = null;

    if(!$permarr['writer']) { //ma uprawnienia ?
        $monit[] = $i18n['edit_note'][1];
    }
    if (!isset($_POST['assign2cat'])) { //zaznaczyl kategorie ?
        $monit[] = $i18n['edit_note'][5];
    }
    if (empty($title)) { //niepusty tytul ?
        $monit[] = $i18n['edit_note'][6];
    }
    if (!preg_match('#^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$#i', $date)) { //wlasciwy format czasu ?
        $monit[] = $i18n['edit_note'][2];
    }
    if(!empty($_FILES['file']['name'])) { //wgrywamy zdjecie ?
        //jesli tak, to do katalogu tymczasowego. pozniej, jesli wsio bedzie
        //w porzadku, przenosimy do docelowego
        //kasowanie z tymczasowego bedzie odbywac sie automatycznie co
        //jakis czas

        $up = new upload;

        // use function to upload file.
        $filename = $up->upload_file(TMPDIR, 'file', true, true, 0, 'jpg|jpeg|gif|png');
        if(!$filename) {

            $monit[] = $up->error;

            $_SESSION['editNote_fileName'] = null;
            unset($_SESSION['editNote_fileName']);
        } else {
            $_SESSION['editNote_fileName'] = $filename;
        }
    }


    
    if (!count($monit)) { //jesli nie bylo bledow, to GO ON!
        //sprawdzamy foto
        $query = sprintf("
            SELECT
                image
            FROM
                %1\$s
            WHERE
                id = %2\$d",

            TABLE_MAIN,
            $_GET['id']
        );
        $db->query($query);
        $db->next_record();
        $filename_old = $db->f('image');

        $filename_new = $filename_old;
        if (!isset($_SESSION['editNote_fileName']) || $filename_old != $_SESSION['editNote_fileName']) {
            @unlink(ROOT . 'photos/' . $filename_old);
            $filename_new = '';
        }
        if (isset($_SESSION['editNote_fileName'])) {
            $filename_new = $_SESSION['editNote_fileName'];

            @rename(TMPDIR . $filename_new, ROOT . 'photos/' . $filename_new);
        }



        //uaktualniamy newsa
        $query = sprintf("
            UPDATE
                %1\$s
            SET
                date = '%2\$s',
                title = '%3\$s',
                author = '%4\$s',
                text = '%5\$s',
                image = '%6\$s',
                comments_allow = %7\$d,
                published = %8\$d,
                only_in_category = %9\$d
            WHERE
                id = %10\$d",

            TABLE_MAIN,
            isset($_POST['now']) ? date('Y-m-d H:i:s') : $_POST['date'],
            $_POST['title'],
            $_POST['author'],
            $_POST['text'],
            $filename_new,
            $_POST['comments_allow'],
            $_POST['published'],
            $_POST['only_in_category'],
            $_GET['id']
        );
        $db->query($query);



        //uaktualniamy kategorie
        $query = sprintf("
            DELETE FROM
                %1\$s
            WHERE
                news_id = %2\$d",
                
            TABLE_ASSIGN2CAT,
            $_GET['id']
        );
        $db->query($query);

        $query = sprintf("
            INSERT INTO
                %1\$s
            VALUES",

            TABLE_ASSIGN2CAT
        );

        $values = array();
        foreach ($_POST['assign2cat'] as $selected_cat) {
            $values[] = sprintf("
                ('', %1\$d, %2\$d)", 

                $_GET['id'],
                $selected_cat
            );
        }
        $query .= implode(',', $values);

        $db->query($query);
        

        header('Location: main.php?p=16&msg=5');
        exit;
    }

    $ft->assign('NOTE_PREVIEW', str_nl2br($_POST['text']));
} elseif (isset($_POST['sub_preview'])) { //podglad wpisanej tresci
    $ft->assign('NOTE_PREVIEW', str_nl2br($_POST['text']));
} elseif (isset($_POST['sub_img_delete'])) { //usuwamy foto
    $_SESSION['editNote_fileName'] = null;
    unset($_SESSION['editNote_fileName']);

    $monit[] = $i18n['edit_note'][4];
}


function get_news_from_post($id_news) {
    $data['date']   = isset($_POST['now']) ? date('Y-m-d H:i:s') : $_POST['date'];
    $data['title']  = str_entit($_POST['title']);
    $data['text']   = str_entit($_POST['text']);
    $data['author'] = str_entit($_POST['author']);
    $data['id']     = $id_news;
    $data['oic']    = (@$_POST['only_in_category'] == 1);
    $data['ca']     = (@$_POST['comments_allow'] == 1);
    $data['p']      = (@$_POST['published'] == 1);
    $data['image']  = isset($_SESSION['editNote_fileName']) ? $_SESSION['editNote_fileName'] : '';
    $data['now']    = isset($_POST['now']);

    /*
    if (isset($_SESSION['editNote_fileNameNew'])) {
        $data['image'] = $_SESSION['editNote_fileNameNew'];
    } elseif (isset($_SESSION['editNote_fileNameOld'])) {
        $data['image'] = $_SESSION['editNote_fileNameOld'];
    } else {
        $data['image'] = null;
    }
    */
    

    //pobieramy do jakich kategorii nalezy dany wpis
    $data['cats'] = isset($_POST['assign2cat']) ? $_POST['assign2cat'] : array();
    return $data;
}

function get_news_from_db($id_news) {
    global $db;
    $data = array();

    $query = sprintf("
        SELECT
            id,
            DATE_FORMAT(date, '%%Y-%%m-%%d %%T') AS date,
            title,
            author,
            text,
            image,
            comments_allow,
            published, 
            only_in_category
        FROM 
            %1\$s 
        WHERE 
            id = '%2\$d'", 

        TABLE_MAIN,
        $_GET['id']
    );

    $db->query($query);
    $db->next_record();

    $data['date']   = $db->f('date');
    $data['title']  = $db->f('title');
    $data['text']   = str_br2nl($db->f('text'));
    $data['author'] = $db->f('author');
    $data['image']  = $db->f('image');
    $data['id']     = $id_news;
    $data['oic']    = ($db->f('only_in_category') == 1);
    $data['ca']     = ($db->f('comments_allow') == 1);
    $data['p']      = ($db->f('published') == 1);
    $data['now']    = false;

    if ($data['image']) {
        //$_SESSION['editNote_fileNameOld'] = null;
        //$_SESSION['editNote_fileNameNew'] = $data['image'];
        $_SESSION['editNote_fileName'] = $data['image'];
    } else {
        //$_SESSION['editNote_fileNameOld'] = null;
        //$_SESSION['editNote_fileNameNew'] = null;
        $_SESSION['editNote_fileName'] = null;
    }

    //pobieramy do jakich kategorii nalezy dany wpis
    $data['cats'] = array();
    $query = sprintf("
        SELECT
            id,
            news_id,
            category_id
        FROM
            %1\$s
        WHERE
            news_id = %2\$d",
        
        TABLE_ASSIGN2CAT,
        $data['id']
    );
    $db->query($query);
    while ($db->next_record()) {
        $data['cats'][] = $db->f('category_id');
    }

    return $data;
}

function get_news($id_news) {
    return empty($_POST) ? get_news_from_db($_GET['id']) : get_news_from_post($_GET['id']);
}



$data = get_news($_GET['id']);

$oic_y = 'checked="checked"';
$oic_n = '';
$ca_y = 'checked="checked"';
$ca_n = '';
$p_y = 'checked="checked"';
$p_n = '';
$date_now = '';
$date_disabled= '';
if (!$data['oic']) {
    $oic_y = '';
    $oic_n = 'checked="checked"';
}
if (!$data['ca']) {
    $ca_y = '';
    $ca_n = 'checked="checked"';
}
if (!$data['p']) {
    $p_y = '';
    $p_n = 'checked="checked"';
}
if ($data['now']) {
    $date_now = 'checked="checked"';
    $date_disabled = 'disabled="disabled"';
}

$ft->define(array('form_noteedit' => 'form_noteedit.tpl'));
$ft->assign(array(
    'AUTHOR'		        => $data['author'],
    'DATE' 			        => $data['date'],
    'ID'			        => $_GET['id'],
    'TITLE'                 => $data['title'],
    'TEXT'                  => $data['text'],
    'ONLY_IN_CAT_YES'       => $oic_y,
    'ONLY_IN_CAT_NO'        => $oic_n,
    'COMMENTS_ALLOW_YES'    => $ca_y,
    'COMMENTS_ALLOW_NO'     => $ca_n,
    'PUBLISHED_YES'         => $p_y,
    'PUBLISHED_NO'          => $p_n,
    'IMG_FILENAME'          => $data['image'] !== false ? $data['image'] : false,
    'DATE_NOW'              => $date_now,
    'DATE_DISABLED'         => $date_disabled
));
unset($oic_y, $oic_n, $ca_y, $ca_n, $p_y, $p_n);



//lista kategorii
$cats = db_get_categories();
tpl_categories('CATEGORIES', $cats, 0, $data['cats']);

//ewentualny message
if (count($monit)) tpl_message($monit);

$ft->parse('ROWS', '.form_noteedit');

?>
