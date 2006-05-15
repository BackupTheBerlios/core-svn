<?php
// $Id: rsscomments.php 1213 2005-11-05 13:03:06Z mysz $

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
header("Content-type: application/xml");

require_once("administration/inc/config.php");

$required_classes = array(
    'db_mysql', 
    'fast_template', 
    'view', 
    'db_config', 
    'xml_feed'
);

while(list($c) = each($required_classes)) {
    require_once PATH_TO_CLASSES . '/cls_' . $required_classes[$c] . CLASS_EXTENSION;
}

require_once("inc/common_lib.php");
require_once("inc/main_lib.php");

// mysql_server_version
get_mysql_server_version();

$xml =& new xml_feed();

$lang = $xml->db_conf->get_config('language_set');

$ft  =& new FastTemplate('./templates/' . $lang . '/main/tpl/');

$xml->parse_comments_feed();

?>
