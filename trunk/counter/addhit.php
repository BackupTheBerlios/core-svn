<?php

$Cookie_length = time()+10800;
$expires = $Cookie_length;

$data_base = new MySQL_DB;

$data_base->query("SELECT * FROM $mysql_data[db_table_counter]");
$data_base->next_record();

$hitnumber = $data_base->f("hitnumber");


if(!isset($_COOKIE['devlog_counter'])){
	
	$name 	= "devlog_counter";
	$value 	= "hit";
	
	setcookie($name, $value, $expires);
	
	$data_base = new MySQL_DB;
	$hitnumber = $hitnumber+1;
	
	$data_base->query("UPDATE $mysql_data[db_table_counter] SET hit = '$value', hitnumber = '$hitnumber+1'");
	$data_base->next_record();
	
}else{
	
	//do nothing
}

?>