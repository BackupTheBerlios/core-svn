<?php
// $Id: rss.php 1134 2005-08-03 23:58:22Z lark $

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

$xml->parse_news_feed();

?>