<?php

define('PATH_TO_CLASSES', '../administration/classes');
define('EXTENSION', '.php');

require_once(PATH_TO_CLASSES. "/cls_db_mysql.php"); // dodawanie pliku konfigurujacego bibliotekê baz danych
require_once(PATH_TO_CLASSES. "/cls_fast_template.php");
require_once(PATH_TO_CLASSES. "/cls_permissions.php");

$lang = !empty($_POST['lang']) ? $_POST['lang'] : 'en';

require_once('inc/i18n_' . $lang . EXTENSION);

// inicjowanie klasy, wkazanie katalogu przechowuj±cego szablony
$ft = new FastTemplate("./templates/" . $lang);

$ft_path = $ft->get_root();

$ft->define('main', "main.tpl");
$ft->assign('CSS_HREF', $ft_path . "/style/style.css");

$p = empty($_GET['p']) ? '' : $_GET['p'];
switch($p){
			
	default:
		include("modules/main_content.php");
		$ft->parse('MAIN', 'main');
}


$ft->FastPrint();
exit;

?>