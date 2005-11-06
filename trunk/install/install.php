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

header('Content-type: text/html; charset=UTF8');

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
