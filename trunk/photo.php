<?php

require_once('inc/i18n.php');
require_once('inc/main_functions.php');

define('PATH_TO_CLASSES', get_root() . '/administration/classes');

require(PATH_TO_CLASSES . '/cls_db_mysql.php'); // dodawanie pliku konfigurujacego bibliotek� baz danych
require(PATH_TO_CLASSES . '/cls_fast_template.php');
require('administration/inc/config.php');

if(isset($_COOKIE['devlog_design']) && is_dir('./templates/' . $_COOKIE['devlog_design'] . '/tpl/')){

    $theme = $_COOKIE['devlog_design'];
} elseif (is_dir('./templates/main/tpl')) {

    $theme = 'main';
} else {

    printf('<div style="font-family: Arial, sans-serif; font-size: 16px; background-color: #ccc; border: 1px solid red; padding: 15px; text-align: center;">%s</div>', $i18n['design'][0]);
    exit;
}

@setcookie('devlog_design', $theme, time() + 3600 * 24 * 365);


// inicjowanie klasy, wkazanie katalogu przechowuj�cego szablony
$ft = new FastTemplate('./templates/' . $theme . '/tpl/');

$db = new DB_SQL;

$ft->define(array(
    'photo_main'    =>'photo_main.tpl',
    'note_main'     =>'note_main.tpl',
    'rows'          =>'rows.tpl',
    'query_failed'  =>'query_failed.tpl'
));

// set {TITLE} variable
// tytu� strony, wy�wietlany w miejscu title::db
$query = sprintf("
    SELECT
        config_value
    FROM
        %s
    WHERE
        config_name = 'title_page'",

    $mysql_data['db_table_config']
);
$db->query($query);
$db->next_record();

$ft->assign('TITLE', $db->f('config_value'));

include('modules/photo_view.php');
$ft->parse('MAIN', array('note_main', 'photo_main'));
$ft->FastPrint();
exit;

?>