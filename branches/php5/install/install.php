<?php

define('PATH_TO_CLASSES', '../administration/classes');
define('EXTENSION', '.php');
define('SQL_SCHEMA', 'dbschema/');

function __autoload($classname) {
    require_once(PATH_TO_CLASSES. '/cls_' . $classname . '.php');
}

$lang = !empty($_POST['lang']) ? $_POST['lang'] : 'pl';

require_once('i18n/' . $lang . '/i18n.php');
require_once('../inc/common_lib.php');

$ft = new fast_template("./templates/" . $lang);

$ft_path = $ft->get_root();

$ft->define('main', "main.tpl");
$ft->assign('CSS_HREF', $ft_path . "/style/style.css");

if(!empty($_POST['post'])) {
    
    $doit = new install();

} else {
    
    $ft->define('main_content', 'main_content.tpl');
    $ft->define_dynamic('lang_row', 'main_content');
        
    $templates_dir = 'templates/';
    $read_dir = @dir($templates_dir);
        
    while($d = $read_dir->read()) {
        if($d[0] != '.') {
                
            $ft->assign(array(
                'SELECTED_LANG' =>$d, 
                'CURRENT'       =>$lang == $d ? 'selected="selected"' : ''
            ));
            $ft->parse('LANG_ROW', '.lang_row');
        }
    }

    $ft->assign(array(
        'HOST'      =>'localhost',
        'PREFIX'    =>'core_'
    ));
        
    $ft->parse('ROWS', "main_content");
}

$ft->parse('MAIN', 'main');
$ft->FastPrint();
exit;

?>