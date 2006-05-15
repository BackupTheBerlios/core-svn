<?php
// $Id: edit_links.php 1213 2005-11-05 13:03:06Z mysz $

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
    
    default:
        if (isset($_POST['sub_delete'])) {
            $link->multidelete();
        } else {
            $link->list_links();
        }
}

?>
