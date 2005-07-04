<?php

// deklaracja zmiennej $action::form
$action     = empty($_GET['action']) ? '' : $_GET['action'];

$ft->define("error_reporting", "error_reporting.tpl");
$ft->define_dynamic("error_row", "error_reporting");

$link = new links();

switch ($action) {
	
    case "show":        $link->show($_GET['id']);   break;
    case "edit":        $link->edit($_GET['id']);   break;
    case "remark":      $link->remark($_GET['id']); break;
    case "delete":      $link->delete();            break;
    case "multidelete": $link->multidelete();       break;
    
    default:            $link->list_links();
}

?>