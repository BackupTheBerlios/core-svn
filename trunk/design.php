<?php
// $Id$

/*
 * This file is internal part of Core CMS (http://core-cms.com/) engine.
 *
 * Copyright (C) 2004-2005 Core Dev Team (more info: docs/AUTHORS).
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published
 * by the Free Software Foundation; version 2 only.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 */

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
