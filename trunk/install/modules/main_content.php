<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {
	
	case "send":

		
		break;

	default:
		
		$ft->assign('PREFIX', 'core_');
		$ft->define('main_content', "main_content.tpl");
		$ft->parse('ROWS', ".main_content");
		break;
}	

?>