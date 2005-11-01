<?php
// $Id$

//domyslnie niech sobie bedzie puste
$ft->assign('MESSAGE', '');
$monit = array();

$CoreNews = new CoreNews;

if (isset($_POST['sub_commit'])) { //ktos probuje dodac wpis...
    if (!$CoreNews->news_add()) {
        $monit = $CoreNews->error_get();
    } else {
        header('Location: main.php?p=1&msg=6');
        exit;
    }
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
if (isset($_POST['sub_preview']) || isset($_POST['sub_commit']))
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
