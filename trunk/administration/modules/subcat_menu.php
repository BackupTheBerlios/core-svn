<?php

$p = empty($_GET['p']) ? '' : $_GET['p'];
switch($p){
	
	case '1':
	case '2':
	case '5':
	case '6':
		$ft->define('menu_news', "menu_news.tpl");
		$ft->parse('SUBCAT_MENU', ".menu_news");
		break;
		
	case '3':
	case '4':
		$ft->define('menu_pages', "menu_pages.tpl");
		$ft->parse('SUBCAT_MENU', ".menu_pages");
		break;
		
	case '7':
	case '13':
		$ft->define('menu_users', "menu_users.tpl");
		$ft->parse('SUBCAT_MENU', ".menu_users");
		break;
		
	case '8':
	case '9':
		$ft->define('menu_category', "menu_category.tpl");
		$ft->parse('SUBCAT_MENU', ".menu_category");
		break;
		
	case '10':
		$ft->define('menu_config', "menu_config.tpl");
		$ft->parse('SUBCAT_MENU', ".menu_config");
		break;
		
	case '11':
	case '12':
		$ft->define('menu_links', "menu_links.tpl");
		$ft->parse('SUBCAT_MENU', ".menu_links");
		break;
		
	case '14':
		$ft->define('menu_templates', "menu_templates.tpl");
		$ft->parse('SUBCAT_MENU', ".menu_templates");
		break;
	
	default:
		break;
}

?>