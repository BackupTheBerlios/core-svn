<?php
// $Id$

if(empty($_GET['id'])) {
    // jesli ktos probuje grzebac w adresie url
    header("Location: index.php");
    exit;
}

require_once('inc/common_lib.php');
require_once('administration/inc/config.php');

require_once(PATH_TO_CLASSES . '/cls_db_mysql.php');
require_once(PATH_TO_CLASSES . '/cls_fast_template.php');

// mysql_server_version
get_mysql_server_version();

$lang = get_config('language_set');

require_once('i18n/' . $lang . '/i18n.php');

// template & design switcher
$theme = prepare_template($lang, $i18n);

@setcookie('devlog_design', $theme, time() + 3600 * 24 * 365);

// inicjowanie klasy, wkazanie katalogu przechowuj±cego szablony
$ft = new FastTemplate('./templates/' . $lang . '/' . $theme . '/tpl/');
$db = new DB_SQL;

$ft->define('photo_main', 'photo_main.tpl');
$ft->assign('TITLE', get_config('title_page'));

$query = sprintf("
    SELECT 
        image 
    FROM 
        %1\$s 
    WHERE 
        id = '%2\$d' 
    LIMIT 1", 

    $table = empty($_GET['p']) ? TABLE_MAIN : TABLE_PAGES,
    $_GET['id']
);

$db->query($query);
$db->next_record();

$image = $db->f('image');

if(!empty($image)) {
    list($width, $height) = getimagesize("photos/" . $image);

    $ft->assign(array(
        'IMAGE_NAME'    =>$image,
        'IMAGE_WIDTH'   =>$width,
        'IMAGE_HEIGHT'  =>$height, 
        'LANG'          =>$lang, 
        'THEME'         =>$theme
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
