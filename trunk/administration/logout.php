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

session_register("login");
session_register("loggedIn");

if(!isset($_SESSION["loggedIn"])){
    
    header("Location: index.php");
    exit;
}

unset($_SESSION["login"]);
unset($_SESSION["loggedIn"]);

session_destroy();

header("Location: index.php");
exit;
?>
