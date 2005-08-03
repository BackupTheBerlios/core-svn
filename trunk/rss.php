<?php
// $Id$

header("Content-type: application/xml");

define("PATH_TO_CLASSES", "administration/classes");

require_once(PATH_TO_CLASSES. '/cls_db_mysql.php');
require_once(PATH_TO_CLASSES. '/cls_fast_template.php');
require_once(PATH_TO_CLASSES. '/cls_xml_feed.php');

require_once("administration/inc/config.php");

require_once("inc/common_lib.php");
require_once("inc/main_lib.php");

// mysql_server_version
get_mysql_server_version();

$lang = get_config('language_set');

$ft  =& new FastTemplate('./templates/' . $lang . '/main/tpl/');
$xml =& new xml_feed();

$xml->parse_news_feed();

?>
