<?php

$p = empty($_GET['p']) ? '' : $_GET['p'];
switch($p){
	
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
