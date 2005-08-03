<?php

//domyslnie niech sobie bedize puste
$ft->assign('MESSAGE', '');
$monit = array();

if (isset($_POST['sub_commit'])) { //ktos probuje dodac wpis...
    $title      = trim($_POST['title']);
    $date       = isset($_POST['now']) ? date('Y-m-d H:i:s') : $_POST['date'];
    $filename   = '';
    
    if(!$permarr['writer']) { //ma uprawnienia ?
        $monit[] = $i18n['add_note'][2];
    }
    if (!isset($_POST['assign2cat'])) { //zaznaczyl kategorie ?
        $monit[] = $i18n['add_note'][3];
    }
    if (empty($title)) { //niepusty tytul ?
        $monit[] = $i18n['add_note'][4];
    }
    if (!preg_match('#^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$#i', $date)) { //wlasciwy format czasu ?
        $monit[] = $i18n['add_note'][5];
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

            $_SESSION['addNote_fileName'] = null;
            unset($_SESSION['addNote_fileName']);
        } else {
            $_SESSION['addNote_fileName'] = $filename;
        }
    }
    

    
    if (!count($monit)) { //jesli nie ma bledow, to dodajemy
        //najpierw przenosimy zdjecie (jesli jest) z katalogu tymczasowego do
        //docelowego
        if (isset($_SESSION['addNote_fileName']) && ($src_path = TMPDIR . $_SESSION['addNote_fileName'])) {

            @rename($src_path, '../photos/' . $_SESSION['addNote_fileName']);
            $_SESSION['addNote_fileName'] = null;
            unset($_SESSION['addNote_fileName'], $src_path);
        }



        //dodajemy newsa
        $query = sprintf("
            INSERT INTO 
                %1\$s 
            VALUES 
                ('', '%2\$s','%3\$s','%4\$s','%5\$s', '%6\$s', '%7\$d', '%8\$s', '%9\$s')",

            TABLE_MAIN,
            $date,
            $title,
            $_POST['author'],
            parse_markers($_POST['text'], 1),
            $filename,
            $_POST['comments_allow'],
            $_POST['published'], 
            $_POST['only_in_category']
        );

        $db->query($query);



        //przypisujemy go do wlasciwych kategorii
        $query = sprintf("
            INSERT INTO
                %1\$s
            VALUES",

            TABLE_ASSIGN2CAT
        );

        $id = mysql_insert_id($db->link_id());
        $values = array();
        foreach ($_POST['assign2cat'] as $selected_cat) {
            $values[] = sprintf("
                ('', %1\$d, %2\$d)", 

                $id,
                $selected_cat
            );
        }
        $query .= implode(',', $values);

        $db->query($query);

        //DONE!
        header('Location: main.php?p=1&msg=6');
        exit;
    }
} elseif (isset($_POST['sub_img_delete'])) { //kasujemy foto

    if (isset($_SESSION['addNote_fileName'])) {
        $path = TMPDIR . $_SESSION['addNote_fileName'];
        @unlink($path);

        $_SESSION['addNote_fileName'] = null;
        unset($_SESSION['addNote_fileName'], $path);
    }

    $monit[] = $i18n['add_note'][7];
}



//jesli nie ma $date, to ja ustawiamy
if (!isset($_POST['now']) && isset($_POST['date'])) {
    $date = $_POST['date'];
} else {
    $date = date('Y-m-d H:i:s');
}


//ustalamy wartosci switchy:

//wartosci domyslne:
//only_in_category
$oic_y = '';
$oic_n = 'checked="checked"';
//comments_allow
$ca_y = 'checked="checked"';
$ca_n = '';
//published
$p_y = 'checked="checked"';
$p_n = '';
//date disabled
$date_disabled = '';
$date_now = '';

//podglad tresci wpisu rowniez przy bledach
if (isset($_POST['sub_preview']) || isset($_POST['sub_commit']) || isset($_POST['sub_img_delete']))
{
    $text   = stripslashes($_POST['text']);
    $title  = trim($_POST['title']);

    $ft->assign(array(
        'N_TITLE'       =>stripslashes($title), 
        'N_TEXT'        =>str_br2nl($text), 
        'NT_TEXT'       =>nl2br(parse_markers($text, 1)), 
        'NOTE_PREVIEW'  =>true
    ));
    $current_cat_id = isset($_POST['assign2cat']) ? $_POST['assign2cat'] : array();
    
    if ($_POST['only_in_category'] > 0) {
        $oic_y = 'checked="checked"';
        $oic_n = '';
    }
    if ($_POST['comments_allow'] <= 0) {
        $ca_y = '';
        $ca_n = 'checked="checked"';
    }
    if ($_POST['published'] < 0) {
        $p_y = '';
        $p_n = 'checked="checked"';
    }
    if (isset($_POST['now'])) {
        $date_disabled = 'disabled="disabled"';
        $date_now = 'checked="checked"';
    }
} else {
    $current_cat_id = array(1);
    $ft->assign('NOTE_PREVIEW', false);
}



//sprawdzamy czy byla proba uploadu zdjecia. jesli nie bylo nic
//submitowane, tzn ze na pewno nie bylo, a zostala jakas stara
//zmienna sesyjna i ja usuwamy
$ft->assign('IMG_FILENAME', false); //domyslnie nie ma zadnego zdjecia

if (isset($_SESSION['addNote_fileName'])) {
    if (empty($_POST)) {
        $_SESSION['addNote_fileName'] = null;
        unset($_SESSION['addNote_fileName']);

    //skoro w _POST cos jest, i jest takze cos w sesji dot. zdjecia,
    //to wyswietlamy nazwe zdjecia 
    } else {
        $path = TMPDIR . $_SESSION['addNote_fileName'];
        if (!is_file($path)) {

            $_SESSION['addNote_fileName'] = null;
            unset($_session['addNote_fileName']);
        } else {

            $ft->assign('IMG_FILENAME', $_SESSION['addNote_fileName']);
        }
    }
}



$ft->define('form_noteadd', 'form_noteadd.tpl');
$ft->assign(array(
    'ONLY_IN_CAT_Y'     => $oic_y,
    'ONLY_IN_CAT_N'     => $oic_n,
    'COMMENTS_ALLOW_Y'  => $ca_y,
    'COMMENTS_ALLOW_N'  => $ca_n,
    'PUBLISHED_Y'       => $p_y,
    'PUBLISHED_N'       => $p_n,
    'DATE'              => $date,
    'AUTHOR'            => isset($_POST['author']) ? str_entit($_POST['author']) : $_SESSION['login'],
    'DATE_DISABLED'     => $date_disabled,
    'DATE_NOW'          => $date_now
));
unset($oic_y, $oic_n, $ca_y, $ca_n, $p_y, $p_n);


//wyswietlamy jakis komunikat ?
if (isset($_GET['msg']) && is_numeric($_GET['msg'])) {
    $monit[] = $i18n['add_note'][$_GET['msg']];
}
if (count($monit)) {
    tpl_message($monit);
}


//lista kategorii
$cats = db_get_categories();
tpl_categories('CATEGORIES', $cats, 0, $current_cat_id);



$ft->parse('ROWS', '.form_noteadd');

?>
