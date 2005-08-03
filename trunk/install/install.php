<?php
// $Id$

define('PATH_TO_CLASSES', '../administration/classes');
define('EXTENSION', '.php');

require_once(PATH_TO_CLASSES. "/cls_db_mysql.php");
require_once(PATH_TO_CLASSES. "/cls_fast_template.php");
require_once(PATH_TO_CLASSES. "/cls_permissions.php");

$lang = !empty($_POST['lang']) ? $_POST['lang'] : 'pl';

require_once('i18n/' . $lang . '/i18n.php');

$ft = new FastTemplate("./templates/" . $lang);

$ft_path = $ft->get_root();

$ft->define('main', "main.tpl");
$ft->assign('CSS_HREF', $ft_path . "style/style.css");

$p = empty($_GET['p']) ? '' : $_GET['p'];
switch($p){
			
	default:
		include("modules/main_content.php");
		$ft->parse('MAIN', 'main');
}

$ft->FastPrint();
exit;

?>
