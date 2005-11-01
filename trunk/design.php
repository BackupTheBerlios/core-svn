<?php
// $Id$

require_once('administration/inc/config.php');
require_once('inc/common_lib.php');

define('PATH_TO_CLASSES', ROOT . 'administration/classes');

require_once(PATH_TO_CLASSES. 'cls_db_mysql.php');

// DB class init
$db = new DB_Sql;

// mysql_server_version
get_mysql_server_version();

$lang = get_config('language_set');

if(isset($_GET['issue']) && is_dir('./templates/' . $lang . '/' . $_GET['issue'] . '/tpl/')){
			
	$theme = $_GET['issue'];
} else {

	$theme = 'main';
}

@setcookie('devlog_design', $theme, time() + 3600 * 24 * 365);

header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']));
exit;
?>
