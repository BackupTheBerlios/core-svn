<?php

class MySQL_DB extends DB_Sql {
	
	var $Host		= "localhost"; // database host
	var $Database 	= "devlog"; // database used for management
	var $User 		= "lark"; // database user
	var $Password 	= "trustno1"; // database password
	
}

define('PREFIX', 'devlog_');

$mysql_data = array('db_table' 				=>"devlog",
					'db_table_users'		=>PREFIX . "users",
					'db_table_comments'		=>PREFIX . "comments",
					'db_table_config'		=>PREFIX . "config",
					'db_table_counter'		=>PREFIX . "counter",
					'db_table_newsletter'	=>PREFIX . "newsletter",
					'db_table_category'		=>PREFIX . "category",
					'db_table_pages'		=>PREFIX . "pages",
					'db_table_links'		=>PREFIX . "links");



$days_to = 360; // liczba dni po których zaczyna dzialac archwium

?>