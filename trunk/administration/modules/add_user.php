<?php

//require_once("classes/cls_user.php");

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {
    
	case "add":

		$monit    = array();
		
		if(strlen($_POST['login_name']) < 4) {
			
			$monit[] = "Nazwa u¿ytkownika musi mieæ conajmniej 4 znaki.";
		}
		
		if(!check_mail($_POST['email'])){
			
			$monit[] = "Podaj poprawny adres e-mail.";
		}
		
		if(strlen($_POST['password']) < 6) {
				
			$monit[] = "Has³o nowego u¿ytkownika musi mieæ conajmniej 6 znaków.";
		}
		
		if($_POST['password'] != $_POST['password_repeat']) {
				
			$monit[] = "Podane has³a nowego u¿ytkownika nie zgadzaj± siê ze sob±.";
		}
		
        $query	= sprintf("
            SELECT * FROM 
                %1\$s 
            WHERE 
                login = '%2\$s'", 
        
            $mysql_data['db_table_users'], 
            $_POST['login_name']
        );
        
        $db->query($query);
        
        if($db->next_record() > 0) {
            
            $monit[] = "U¿ytkownik o loginie <b>" . $_POST['login_name'] . "</b> znajduje siê ju¿ w bazie danych.<br />";
        }
		
		if(!empty($monit)) {
			
			$ft->define("error_reporting", "error_reporting.tpl");
            $ft->define_dynamic("error_row", "error_reporting");

            foreach ($monit as $error) {
    
                $ft->assign('ERROR_MONIT', $error);
                    
                $ft->parse('ROWS',	".error_row");
            }
                        
            $ft->parse('ROWS', "error_reporting");
			
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
			'SUBMIT_URL'		=>"main.php?p=7&amp;action=add"));
			
		$ft->define('form_useradd', "form_useradd.tpl");
		$ft->parse('ROWS', ".form_useradd");
		break;
}

?>