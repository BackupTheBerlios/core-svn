<?php

require_once("classes/cls_user.php");

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action)
{
	case "add":
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
			
			$date		= date("Y-m-d H:i:s");
			
			$login		= $_POST['login_name'];
			$password	= md5($_POST['password']);
			$email		= $_POST['email'];
			
			// egzemplarz klasy ³aduj±cej komentarz do bazy danych
			$db = new MySQL_DB;
			
			$db->query("INSERT INTO $mysql_data[db_table_users] VALUES ('', '$login', '$password', '$email', 'N')");
			
			$ft->assign('CONFIRM', "U¿ytkownik zosta³ dodany do bazy danych");
			$ft->parse('ROWS', ".result_note");
			
		}
		break;

	default:
		// w przypadku braku akcji wy¶wietlanie formularza
		$ft->parse('ROWS', ".form_useradd");
}

?>
