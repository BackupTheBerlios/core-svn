<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

$db = new MySQL_DB;

switch ($action) {
	
	case "show": // wy¶wietlanie wpisu pobranego do modyfikacji
	
		$query = sprintf("
					SELECT * FROM 
						$mysql_data[db_table_users] 
					WHERE 
						id = '%1\$d'", $_GET['id']);
		
		$db->query($query);
		$db->next_record();
		
		$user_id		= $db->f("id");
		$user_name		= $db->f("login");
		$user_email		= $db->f("email");
		$user_status	= $db->f("active");
		
		$ft->assign(array(	'USER_ID'			=>$user_id,
							'USER_NAME'			=>$user_name,
							'LINK_EMAIL'		=>$user_email,
							'SUBMIT_URL'		=>"edit," . $user_id . ",13,edit.html",
							'LINK_VALUE'		=>"value=\"" . $user_name . "\"",
							'LINKEMAIL_VALUE'	=>"value=\"" . $user_email . "\"",
							'SUBMIT_HREF_DESC'	=>"zmodyfikuj dane u¿ytkownika",
							'HEADER_DESC'		=>"<b>U¿ytkownicy - modyfikacja u¿ytkownika</b>"));

		$ft->define('form_useradd', "form_useradd.tpl");
		$ft->parse('ROWS',	".form_useradd");
		break;

	case "edit":// edycja wybranego wpisu
		$user_name	= $_POST['login_name'];
		$user_email	= $_POST['email'];
		
		$query = sprintf("
					UPDATE 
						$mysql_data[db_table_users] 
					SET 
						login	= '$user_name', 
						email	= '$user_email' 
					WHERE 
						id = '%1\$d'", $_GET['id']);
		
		$db->query($query);
		
		$ft->assign('CONFIRM', "U¿ytkownik zosta³ zmodyfikowany.");
		$ft->parse('ROWS',	".result_note");
		break;

	case "delete":// usuwanie wybranego wpisu
	
		$query = sprintf("
					DELETE FROM 
						$mysql_data[db_table_users] 
					WHERE 
						id = '%1\$d'", $_GET['id']);
		
		$db->query($query);
		
		$ft->assign('CONFIRM', "U¿ytkownik zosta³ usuniêty.");
		$ft->parse('ROWS', ".result_note");
		break;

	default:
	
		$query = "	SELECT * FROM 
						$mysql_data[db_table_users] 
					ORDER BY 
						id 
					ASC";
		$db->query($query);
	
		// Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
		while($db->next_record()) {
		
			$user_id		= $db->f("id");
			$user_name		= $db->f("login");
			$user_email		= $db->f("email");
			$user_status	= $db->f("active");
			
			$ft->assign(array(
						'USER_ID'		=>$user_id,
						'USER_NAME'		=>$user_name,
						'USER_EMAIL'	=>$user_email,
						'USER_STATUS'	=>$user_status
			));			
			
			// deklaracja zmiennej $idx1::color switcher
			$idx1 = empty($idx1) ? '' : $idx1;
			
			$idx1++;
			
			$ft->define('table_userslist', "table_userslist.tpl");
			// naprzemienne kolorowanie wierszy tabeli
			if (($idx1%2)==1) {
				
				$ft->assign('ID_CLASS', "class=\"mainList\"");
				// parsowanie szablonów
				$ft->parse('NOTE_ROWS',	".table_userslist");
			} else {
				
				$ft->assign('ID_CLASS', "class=\"mainListAlter\"");
				$ft->parse('NOTE_ROWS',	".table_userslist");
			}
		}
		$ft->define('header_userslist', "header_userslist.tpl");
		$ft->parse('ROWS',	".header_userslist");
}

?>
