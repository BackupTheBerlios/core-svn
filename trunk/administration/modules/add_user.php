<?php

//require_once("classes/cls_user.php");

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

// definicja szablonow parsujacych wyniki bledow.
$ft->define("error_reporting", "error_reporting.tpl");
$ft->define_dynamic("error_row", "error_reporting");

switch ($action) {
    
	case "add":
	
        if($permarr['moderator']) {

            $monit    = array();
		
            if(strlen($_POST['login_name']) < 4) $monit[] = $i18n['add_user'][0];
            if(strlen($_POST['password']) < 6) $monit[] = $i18n['add_user'][2];
		
            if(!check_mail($_POST['email'])) $monit[] = $i18n['add_user'][1];
		
            if($_POST['password'] != $_POST['password_repeat']) $monit[] = $i18n['add_user'][3];
		
            $query	= sprintf("
                SELECT * FROM 
                    %1\$s 
                WHERE 
                    login = '%2\$s'", 
        
                TABLE_USERS, 
                $_POST['login_name']
            );
        
            $db->query($query);
        
            if($db->next_record() > 0) {
            
                $monit[] = "U¿ytkownik o loginie <b>" . $_POST['login_name'] . "</b> znajduje siê ju¿ w bazie danych.<br />";
            }
		
            if(!empty($monit)) {
			
                foreach ($monit as $error) {
    
                    $ft->assign('ERROR_MONIT', $error);
                    
                    $ft->parse('ROWS',	".error_row");
                }
                            
                $ft->parse('ROWS', "error_reporting");
            } else {
			
                $u_login         = $_POST['login_name'];
                $password        = md5($_POST['password']);
                $email           = $_POST['email'];
			
                $name            = $_POST['name'];
                $surname         = $_POST['surname'];
                $city            = $_POST['city'];
                $country         = $_POST['country'];
			
                $www             = $_POST['www'];
                $gg              = $_POST['gg'];
                $tlen            = $_POST['tlen'];
                $jid             = $_POST['jid'];
			
                $hobby           = $_POST['hobby'];
                $additional_info = $_POST['additional_info'];
			
                $query = sprintf("
                    INSERT INTO 
                        %1\$s 
                    VALUES 
                        ('', '%2\$s', '%3\$s', '%4\$s', '%5\$d', 'Y', '%6\$s', '%7\$s', '%8\$s', '%9\$s', '%10\$s', '%11\$s', '%12\$s', '%13\$s', '%14\$s', '%15\$s')",
			
                    TABLE_USERS,
                    $u_login,
                    $password,
                    $email,
                    1,
                    $name,
                    $surname,
                    $city,
                    $country,
                    $www,
                    $gg,
                    $tlen,
                    $jid,
                    $hobby,
                    $additional_info
                );
            
                $db->query($query);
			
                $ft->assign('CONFIRM', $i18n['add_user'][4]);
                $ft->parse('ROWS', ".result_note");
            }
        } else {
            
            $monit[] = $i18n['add_user'][6];

            foreach ($monit as $error) {
    
                $ft->assign('ERROR_MONIT', $error);
                    
                $ft->parse('ROWS',	".error_row");
            }
                        
            $ft->parse('ROWS', "error_reporting");
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
