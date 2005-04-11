<?php

//require_once("classes/cls_user.php");

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {
    
	case "add":

		$err      = ""; // zmienna przechowuj±ca b³edy
		$monit    = array();
		
		$monit['strlenuser']	= "Nazwa u¿ytkownika musi mieæ conajmniej 4 znaki.";
		$monit['strlenpass']	= "Has³o nowego u¿ytkownika musi mieæ conajmniej 6 znaków.";
		$monit['validemail']	= "Podaj poprawny adres e-mail.";
		$monit['diffpass']		= "Podane has³a nowego u¿ytkownika nie zgadzaj± siê ze sob±.";
		
		if(strlen($_POST['login_name']) < 4) {
			
			$err .= $monit['strlenuser'] . "<br />";
		}
		
		if(!check_mail($_POST['email'])){
			
			$err .= $monit['validemail'] . "<br />";
		}
		
		if(strlen($_POST['password']) < 6) {
				
			$err .= $monit['strlenpass'] . "<br />";
		}
		
		if($_POST['password'] != $_POST['password_repeat']) {
				
			$err .= $monit['diffpass'] . "<br />";
		}
		
		if(!empty($err)) {
			
			$err .= "<br /><a href=\"javascript:history.back(-1);\">powrót</a>";

			$ft->assign('CONFIRM', $err);
			$ft->parse('ROWS', ".result_note");
			
		} else {
			
			$login		= $_POST['login_name'];
			$password	= md5($_POST['password']);
			$email		= $_POST['email'];
			
			$query = sprintf("
                INSERT INTO 
                    %1\$s 
                VALUES 
                    ('', '$login', '$password', '$email', '1', 'Y')",
			
                $mysql_data['db_table_users']
            );
            
            $db->query($query);
			
			$ft->assign('CONFIRM', "U¿ytkownik zosta³ dodany do bazy danych");
			$ft->parse('ROWS', ".result_note");
			
		}
		break;

	default:
		// w przypadku braku akcji wy¶wietlanie formularza
		$ft->assign(array(
			'SUBMIT_HREF_DESC'	=>"Dodaj u¿ytkownika",
			'SUBMIT_URL'		=>"main.php?p=$7&amp;action=add"));
			
		$ft->define('form_useradd', "form_useradd.tpl");
		$ft->parse('ROWS', ".form_useradd");
		break;
}
?>
