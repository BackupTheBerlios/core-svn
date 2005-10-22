<?php
// $Id: edit_users.php 1128 2005-08-03 22:16:55Z mysz $

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

// definicja szablonow parsujacych wyniki bledow.
$ft->define("error_reporting", "error_reporting.tpl");
$ft->define_dynamic("error_row", "error_reporting");

switch ($action) {
	
	case "show":
	
		$query = sprintf("
            SELECT * FROM 
                %1\$s 
            WHERE 
                id = '%2\$d'", 
		
            TABLE_USERS, 
            $_GET['id']
        );
		
		$db->query($query);
		$db->next_record();
		
		$ft->assign(array(
            'USER_ID'           =>$_GET['id'],
            
            'LOGIN'             =>$db->f('login'),
            'EMAIL'             =>$db->f("email"),
            
            'NAME'              =>$db->f("name"),
            'SURNAME'           =>$db->f("surname"),
            'CITY'              =>$db->f("city"),
            'COUNTRY'           =>$db->f("country"),
            
            'WWW'               =>$db->f("www"),
            'GG'                =>$db->f("gg"),
            'TLEN'              =>$db->f("tlen"),
            'JID'               =>$db->f("jid"),
            
            'HOBBY'             =>$db->f("hobby"),
            'ADDITIONAL_INFO'   =>$db->f("additional_info")
        ));

		$ft->define('form_useredit', "form_useredit.tpl");
		$ft->parse('ROWS',	".form_useredit");
		break;

	case "edit":
	
        $monit = array();
	
        $query = sprintf("
            SELECT * FROM 
                %1\$s 
            WHERE 
                id = '%2\$d'", 
		
            TABLE_USERS,
            $_GET['id']
        );
		
		$db->query($query);
		$db->next_record();
		
		$core_user = $db->f("login");
	
		// sprawdzamy, czy uzytkownik ma odpowiednie uprawniania, lub czy
		// edytuje swoje dane - wowczas access granted
        if($permarr['admin'] || ($permarr['writer'] && $core_user == $_SESSION['login'])) {

            // edycja wybranego wpisu
            $u_login    = $_POST['login_name'];
            $email      = $_POST['email'];
        
            $name       = $_POST['name'];
            $surname    = $_POST['surname'];
            $city       = $_POST['city'];
            $country    = $_POST['country'];
        
            $www        = $_POST['www'];
            $gg         = $_POST['gg'];
            $tlen       = $_POST['tlen'];
            $jid        = $_POST['jid'];
        
            $hobby      = $_POST['hobby'];
        
            $additional_info    = $_POST['additional_info'];
        
            if(!check_mail($email)){
                $monit[] = $i18n['edit_users'][9];
            }
		
            if(!empty($monit)) {

                foreach ($monit as $error) {
    
                    $ft->assign('ERROR_MONIT', $error);
                    
                    $ft->parse('ROWS',	".error_row");
                }
                        
                $ft->parse('ROWS', "error_reporting");
            
            } else {
		
                $query = sprintf("
                    UPDATE 
                        %1\$s 
                    SET 
                        login           = '%2\$s', 
                        email           = '%3\$s', 
                    
                        name            = '%4\$s', 
                        surname         = '%5\$s',
                        city            = '%6\$s', 
                        country         = '%7\$s',
                    
                        www             = '%8\$s',
                        gg              = '%9\$s',
                        tlen            = '%10\$s', 
                        jid             = '%11\$s',
                    
                        hobby           = '%12\$s', 
                        additional_info = '%13\$s'
                    WHERE 
                        id = '%14\$d'", 
		
                    TABLE_USERS, 
                
                    $u_login, 
                    $email, 
                
                    $name,
                    $surname,
                    $city,
                    $country,
                
                    $www,
                    $gg,
                    $tlen,
                    $jid, 
                
                    $hobby, 
                    $additional_info,
                
                    $_GET['id']
                );
            
                $db->query($query);
                $ft->assign('CONFIRM', $i18n['edit_users'][1]);
                $ft->parse('ROWS',	".result_note");
            }
        } else {
            
            $monit[] = $i18n['edit_users'][4];

            foreach ($monit as $error) {
    
                $ft->assign('ERROR_MONIT', $error);
                    
                $ft->parse('ROWS',	".error_row");
            }
                        
            $ft->parse('ROWS', "error_reporting");
        }
		break;

	case "delete":
	
        // potwierdzenie usuniecia u¿ytkownika
        $confirm = empty($_POST['confirm']) ? '' : $_POST['confirm'];
        switch ($confirm) {
            
            case $i18n['confirm'][0]:
            
                $post_id = empty($_POST['post_id']) ? '' : $_POST['post_id'];
	
                if($permarr['admin']) {
	
                    $query = sprintf("
                        SELECT 
                            login
                        FROM 
                            %1\$s 
                        WHERE 
                            id = '%2\$d'",
        
                        TABLE_USERS, 
                        $post_id
                    );
        
                    $db->query($query);
                    $db->next_record();
            
                    $u_login = $db->f("login");
            
                    if($u_login == $_SESSION['login']) {
		    
                        $ft->assign('CONFIRM', $i18n['edit_users'][0]);
                    } else {
	   
                        $query = sprintf("
                            DELETE FROM 
                                %1\$s 
                            WHERE 
                                id = '%2\$d'", 
		
                            TABLE_USERS, 
                            $post_id
                        );
            
                        $db->query($query);
            
                        $ft->assign('CONFIRM', $i18n['edit_users'][2]);
                    }
            
                    $ft->parse('ROWS', ".result_note");
                } else {
            
                    $monit[] = $i18n['edit_users'][3];

                    foreach ($monit as $error) {
    
                        $ft->assign('ERROR_MONIT', $error);
                    
                        $ft->parse('ROWS',	".error_row");
                    }
                        
                    $ft->parse('ROWS', "error_reporting");
                }
            break;

        case $i18n['confirm'][1]:
        
            header("Location: main.php?p=13");
            exit;
            break;
            
        default:
        
            $ft->define('confirm_action', 'confirm_action.tpl');
            $ft->assign(array(
                'PAGE_NUMBER'   =>$p, 
                'POST_ID'       =>$_GET['id'], 
                'CONFIRM_YES'   =>$i18n['confirm'][0],
                'CONFIRM_NO'    =>$i18n['confirm'][1]
            ));
            
            $ft->parse('ROWS', ".confirm_action");
            break;
        }
    break;

	default:
	
        $plevel = empty($_GET['plevel']) ? '' : $_GET['plevel'];
		    
		if(!empty($plevel)) {
		    
		    $query = sprintf("
                SELECT 
                    permission_level 
                FROM 
                    %1\$s 
                WHERE 
                    id = %2\$d 
                LIMIT
                    1", 
		    
                TABLE_USERS,
                $_GET['id']
            );
            
            $db->query($query);
            $db->next_record();
            
            $permission_level = $db->f("permission_level");
            
            switch ($permission_level) {
                
                case '1': $p_level = 1;
                break;
                
                case '3': $p_level = 2;
                break;
                
                case '7': $p_level = 3;
                break;
                
                case '15': $p_level = 4;
                break;
                
                case '31': $p_level = 5;
                break;
            }
            
            $new_permissions = new permissions;
            
            if($plevel == "down") {
                
                if($p_level == 1) {
                        
                    $ft->assign('CONFIRM', $i18n['edit_users'][8]);
                } else {
                        
                    $p_level = $p_level-1;
                        
                    switch ($p_level) {
                            
                        case '3':
                            $new_permissions->permissions["user"]                  = TRUE;
                            $new_permissions->permissions["writer"]                = TRUE;
                            $new_permissions->permissions["moderator"]             = TRUE;
                            break;
                                
                        case '2':
                            $new_permissions->permissions["user"]                  = TRUE;
                            $new_permissions->permissions["writer"]                = TRUE;
                            break;
                                
                        case '1':
                            $new_permissions->permissions["user"]                  = TRUE;
                            break;
                    }
                        
                    $mask   = $new_permissions->toBitmask();
                        
                    $query  = sprintf("
                        UPDATE 
                            %1\$s 
                        SET 
                            permission_level = %2\$d 
                        WHERE 
                            id = %3\$d", 
                        
                        TABLE_USERS,
                        $mask,
                        $_GET['id']
                    );
                    
                    $db->query($query);
                        
                    $ft->assign('CONFIRM', $i18n['edit_users'][6]);
                }
                 
             
            }
            
            if($plevel == "up") {
                
                if($p_level == 4) {
                        
                    $ft->assign('CONFIRM', $i18n['edit_users'][7]);
                } else {
                    
                    $p_level = $p_level+1;
                        
                    switch ($p_level) {
                            
                        case '4':
                            $new_permissions->permissions["user"]                  = TRUE;
                            $new_permissions->permissions["writer"]                = TRUE;
                            $new_permissions->permissions["moderator"]             = TRUE;
                            $new_permissions->permissions["tpl_editor"]            = TRUE;
                            break;
                                
                        case '3':
                            $new_permissions->permissions["user"]                  = TRUE;
                            $new_permissions->permissions["writer"]                = TRUE;
                            $new_permissions->permissions["moderator"]             = TRUE;
                            break;
                                
                        case '2':
                            $new_permissions->permissions["user"]                  = TRUE;
                            $new_permissions->permissions["writer"]                = TRUE;
                            break;
                    }
                        
                    $mask   = $new_permissions->toBitmask();
                        
                    $query  = sprintf("
                        UPDATE 
                            %1\$s 
                        SET 
                            permission_level = %2\$d 
                        WHERE 
                            id = %3\$d", 
                        
                        TABLE_USERS,
                        $mask,
                        $_GET['id']
                    );
                    
                    $db->query($query);
                        
                    $ft->assign('CONFIRM', $i18n['edit_users'][5]);
                }
            }
		}
	
		$query = sprintf("
            SELECT * FROM 
                %1\$s 
            ORDER BY 
                id 
            ASC", 
		
            TABLE_USERS
        );
        
		$db->query($query);
	
		while($db->next_record()) {
		
			$user_id     = $db->f("id");
			$u_login     = $db->f("login");
			$email       = $db->f("email");
			$perm_level  = $db->f("permission_level");
			$status      = $db->f("active");
			
			switch ($perm_level) {
			    
			    case '1': $level = 1;    break;
			    case '3': $level = 2;    break;
			    case '7': $level = 3;    break;
			    case '15':$level = 4;    break;
			    case '31':$level = 5;    break;
			}
			
			if($permarr["admin"]){
			        
                switch ($level) {
                    
                    case '1':
                        $ft->assign(array(
                            'PRIVILEGE_DOWN'    =>false,
                            'PRIVILEGE_UP'      =>true
                        ));
                    break;
                    
                    case '2':
                    case '3':
                        $ft->assign(array(
                            'PRIVILEGE_DOWN'    =>true, 
                            'PRIVILEGE_UP'      =>true
                        ));
                    break;
                    
                    case '4':
                        $ft->assign(array(
                            'PRIVILEGE_DOWN'    =>true, 
                            'PRIVILEGE_UP'      =>false
                        ));
                    break;
                    
                    default:
                        $ft->assign(array(
                            'PRIVILEGE_DOWN'    =>false, 
                            'PRIVILEGE_UP'      =>false
                        ));
                    break;
                }
			    
			} else {
			    
			    $ft->assign(array(
                    'PRIVILEGE_DOWN'    =>false, 
                    'PRIVILEGE_UP'      =>false
                ));
			}
			
			$ft->assign(array(
                'USER_ID'   =>$user_id,
                'NAME'      =>$u_login,
                'EMAIL'     =>$email,
                'LEVEL'     =>$level, 
                'STATUS'    =>$status == 'Y' ? $i18n['confirm'][0] : $i18n['confirm'][1]
			));
			
			// deklaracja zmiennej $idx1::color switcher
			$idx1 = empty($idx1) ? '' : $idx1;
			
			$idx1++;
			
			$ft->define("editlist_users", "editlist_users.tpl");
			$ft->define_dynamic("row", "editlist_users");
			
			// naprzemienne kolorowanie wierszy tabeli
			$ft->assign('ID_CLASS', $idx1%2 ? 'mainList' : 'mainListAlter');
			
			$ft->parse('ROW', ".row");
		}
		
		$ft->parse('ROWS', "editlist_users");
		$ft->parse('ROWS', ".result_note");
}

?>
