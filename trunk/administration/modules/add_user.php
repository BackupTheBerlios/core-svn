<?php

//require_once("classes/cls_user.php");

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {
    
	case "add":

		$monit    = array();
		
		if(strlen($_POST['login_name']) < 4) {
			
			$monit[] = $i18n['add_user'][0];
		}
		
		if(!check_mail($_POST['email'])){
			
			$monit[] = $i18n['add_user'][1];
		}
		
		if(strlen($_POST['password']) < 6) {
				
			$monit[] = $i18n['add_user'][2];
		}
		
		if($_POST['password'] != $_POST['password_repeat']) {
				
			$monit[] = $i18n['add_user'][3];
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
			
			$ft->assign('CONFIRM', $i18n['add_user'][4]);
			$ft->parse('ROWS', ".result_note");
			
		}
		break;

	default:
		// w przypadku braku akcji wy¶wietlanie formularza
		$ft->assign(array(
			'SUBMIT_HREF_DESC'	=>$i18n['add_user'][5],
			'SUBMIT_URL'		=>"main.php?p=7&amp;action=add"
        ));
			
		$ft->define('form_useradd', "form_useradd.tpl");
		$ft->parse('ROWS', ".form_useradd");
		break;
}

?>