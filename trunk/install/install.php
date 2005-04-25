<?php

define("PATH_TO_CLASSES",	"../administration/classes");

require_once(PATH_TO_CLASSES. "/cls_db_mysql.php"); // dodawanie pliku konfigurujacego bibliotekę baz danych
require_once(PATH_TO_CLASSES. "/cls_fast_template.php");
require_once(PATH_TO_CLASSES. "/cls_permissions.php");

require_once('../inc/i18n_install.php');

// inicjowanie klasy, wkazanie katalogu przechowującego szablony
$ft = new FastTemplate("./templates");

$ft->define(array(
    'main'      =>"main.tpl",
    'note_main' =>"note_main.tpl"
));
		
$ft->assign(array(
    'TITLE'     =>"Core / Instalator",
    'CSS_HREF'  =>"style/style.css"
));

$p = empty($_GET['p']) ? '' : $_GET['p'];
switch($p){
			
	default:
		include("modules/main_content.php");
		$ft->parse('MAIN', array("note_main", "main"));
}


$ft->FastPrint();
exit;
?>
