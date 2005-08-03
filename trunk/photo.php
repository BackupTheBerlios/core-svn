<?php
// $Id$

if(empty($_GET['id'])) {
    // jesli ktos probuje grzebac w adresie url
    header("Location: index.php");
    exit;
}

require_once('inc/common_lib.php');
require_once('administration/inc/config.php');

$required_classes = array(
    'db_mysql', 
    'fast_template', 
    'view', 
    'db_config'
);

while(list($c) = each($required_classes)) {
    require_once PATH_TO_CLASSES . '/cls_' . $required_classes[$c] . CLASS_EXTENSION;
}

$view       =& view::instance();
$db_conf    =& new db_config;

// mysql_server_version
get_mysql_server_version();

$lang = $db_conf->get_config('language_set');

require_once('i18n/' . $lang . '/i18n.php');

// template & design switcher
$theme = prepare_template($lang, $i18n);

@setcookie('devlog_design', $theme, time() + 3600 * 24 * 365);

$ft =& new FastTemplate('./templates/' . $lang . '/' . $theme . '/tpl/');

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

$view->db->query($query);
$view->db->next_record();

$image = $view->db->f('image');

if(!empty($image)) {
    list($width, $height) = getimagesize("photos/" . $image);

    $ft->assign(array(
        'IMAGE_NAME'    =>$image,
        'IMAGE_WIDTH'   =>$width,
        'IMAGE_HEIGHT'  =>$height, 
        'LANG'          =>$lang, 
        'THEME'         =>$theme
    ));
    
    $ft->define('photo_main', 'photo_main.tpl');
    $ft->assign('TITLE', $db_conf->get_config('title_page'));

    $ft->parse('CONTENT', 'photo_main');
} else {
    // jesli ktos probuje grzebac w adresie url
    header("Location: index.php");
    exit;
}
    
$ft->FastPrint('CONTENT');
exit;

?>