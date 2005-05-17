<?php

if(empty($_GET['id'])) {
    // jesli ktos probuje grzebac w adresie url
    header("Location: index.php");
    exit;
}

require_once('inc/i18n.php');
require_once('inc/main_functions.php');

define('PATH_TO_CLASSES', get_root() . '/administration/classes');

require(PATH_TO_CLASSES . '/cls_db_mysql.php'); // dodawanie pliku konfigurujacego bibliotekê baz danych
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


// inicjowanie klasy, wkazanie katalogu przechowuj±cego szablony
$ft = new FastTemplate('./templates/' . $theme . '/tpl/');
$db = new DB_SQL;

$ft->define('photo_main', 'photo_main.tpl');

// set {TITLE} variable
// tytu³ strony, wy¶wietlany w miejscu title::db
$query = sprintf("
    SELECT
        config_value
    FROM
        %s
    WHERE
        config_name = 'title_page'",

    TABLE_CONFIG
);
$db->query($query);
$db->next_record();

$ft->assign('TITLE', $db->f('config_value'));

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
    
    $db->next_record();

    $image  = $db->f("image");

    list($width, $height) = getimagesize("photos/" . $image);

    $ft->assign(array(
        'IMAGE_NAME'    =>$image,
        'IMAGE_WIDTH'   =>$width,
        'IMAGE_HEIGHT'  =>$height
    ));

    $ft->parse('CONTENT', 'photo_main');
} else {
    // jesli ktos probuje grzebac w adresie url
    header("Location: index.php");
    exit;
}
    
$ft->FastPrint('CONTENT');
exit;

?>