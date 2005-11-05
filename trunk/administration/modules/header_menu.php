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

switch($p = empty($_GET['p']) ? '' : $_GET['p']){
	
	case '1':
	case '2':
	case '5':
	case '6':
		$ft->assign('NEWS_CURRENT', 'id="current"');
		break;
		
	case '3':
	case '4':
		$ft->assign('PAGES_CURRENT', 'id="current"');
		break;
		
	case '7':
	case '13':
		$ft->assign('USERS_CURRENT', 'id="current"');
		break;
		
	case '8':
	case '9':
	case '15':
		$ft->assign('CAT_CURRENT', 'id="current"');
		break;
		
	case '10':
		$ft->assign('CONFIG_CURRENT', 'id="current"');
		break;
		
	case '11':
	case '12':
		$ft->assign('LINKS_CURRENT', 'id="current"');
		break;
		
	case '14':
		$ft->assign('TEMPLATES_CURRENT', 'id="current"');
		break;
	
	default:
		$ft->assign('MAIN_CURRENT', 'id="current"');
		break;
}

$ft->define('menu_header', "menu_header.tpl");
$ft->parse('MENU_HEADER', ".menu_header");

?>
