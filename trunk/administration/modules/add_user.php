<?php

require_once("classes/cls_user.php");

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

if (empty($action)) {
	
	// w przypadku braku akcji wy¶wietlanie formularza
	$ft->parse('ROWS', ".form_useradd");
	
}

if($action == "add") {
	
	// egzemplarz klasy user
	$valid_user = new user();
	
	// Sprawdzenie poprawnoœci wprowadzonych danych
	$valid_user->name_valid($_POST['login_name']);
	$valid_user->pass_valid($_POST['password'], $_POST['password_repeat']);
	$valid_user->email_valid($_POST['email']);
	
	if(!empty($valid_user->monit)) {
		
		$valid_user->monit .= "<br /><a href=\"javascript:history.back(-1);\">powrót</a>";

		$ft->assign('CONFIRM', $valid_user->monit);
		$ft->parse('ROWS', ".result_note");
		
		
	} else {
		
		$tmp		= time();
		$date		= date("Y-m-d H:i:s", $tmp);
		
		$login		= $_POST['login_name'];
		$password	= $_POST['password'];
		$email		= $_POST['email'];
		
		// egzemplarz klasy ³aduj±cej komentarz do bazy danych
		$d_base = new MySQL_DB;
		
		$d_base->query("INSERT INTO $mysql_data[db_table_users] VALUES ('', '$login', '$password', '$email', 'N')");
		$d_base->next_record();
		
		$ft->assign('CONFIRM', "U¿ytkownik zosta³ dodany do bazy danych");
		$ft->parse('ROWS', ".result_note");
		
	}
}
?>