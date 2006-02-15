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
header("Content-type: application/xml");

require_once("administration/inc/config.php");

require_once 'inc/common_lib.php';
require_once pathjoin(ROOT, 'inc', 'main_lib.php');

$required_classes = array(
    'db_mysql.php', 
    'fast_template.php', 
    'db_config.php',
    'view.php',
    'corebase.php',
    'corerss.php', 
    'rss.php'
);

while(list($c) = each($required_classes)) {
    require_once pathjoin(PATH_TO_CLASSES, 'cls_' . $required_classes[$c]);
}

// mysql_server_version
get_mysql_server_version();

$CoreRss = new CoreRss();

$ft =& new FastTemplate('./templates/pl/main/tpl/');

$ft->define('xml_feed', 'xml_feed.tpl');
$ft->define_dynamic('xml_row', 'xml_feed');
$ft->define_dynamic("cat_row", "xml_feed");

$ft->assign(array(
    'MAINSITE_LINK' =>'http://',
    'NEWS_FEED'     =>true
));

$CoreRss->rss_list(null);

foreach($CoreRss->rss as $rss) {
        
    $id = $rss->get_id();
    
    /*
    $ft->assign(array(
           'DATE'          =>date($date_format, $rss->get_timestamp()),
           'NEWS_TITLE'    =>$rss->get_title(),
	       'NEWS_TEXT'     =>$text,
           'NEWS_AUTHOR'   =>$rss->get_author(),
	       'NEWS_ID'       =>$id,
	       'NEWS_CATEGORY' =>'', //TODO
	       'PERMA_LINK'    =>$CoreRewrite->permanent_news($id, $rewrite), 
	       'PAGINATED'     =>!empty($pagination['page_string']) ? true : false, 
	       'STRING'        =>$pagination['page_string']
	    ));
	  
	*/
      
    $ft->assign(array(
        'DATE'          =>date($date_format, $rss->get_timestamp()), 
        'TITLE'         =>$rss->get_title(), 
        'AUTHOR'        =>$rss->get_author(), 
        'PERMALINK'     =>$permanent_link, 
        'TEXT'          =>$text, 
        'COMMENTS_LINK' =>$comments_link, 
        'DISPLAY_XML'   =>true
    ));
    
    $ft->parse('XML_ROW', ".xml_row");

}

?>