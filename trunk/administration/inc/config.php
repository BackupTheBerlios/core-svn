<?php

class MySQL_DB extends DB_Sql {
	
	var $Host		= "localhost"; // database host
	var $Database 	= "devlog"; // database used for management
	var $User 		= "lark"; // database user
	var $Password 	= "trustno1"; // database password
	
}

$mysql_data = array( 	'db_table' 				=>"devlog",
						'db_table_users'		=>"devlog_users",
						'db_table_comments'		=>"devlog_comments",
						'db_table_config'		=>"devlog_config",
						'db_table_counter'		=>"devlog_counter",
						'db_table_newsletter'	=>"devlog_newsletter",
						'db_table_category'		=>"devlog_category",
						'db_table_pages'		=>"devlog_pages");



$stats_per_page = 20; // liczba wpisów statystyk jednoczesnie pokazywana na stronie
$days_to = 360; // liczba dni po których zaczyna dzialac archwium

?>