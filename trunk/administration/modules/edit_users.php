<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {
	
	case "show": // wy¶wietlanie wpisu pobranego do modyfikacji
	
		$query = sprintf("
            SELECT * FROM 
                %1\$s 
            WHERE 
                id = '%2\$d'", 
		
            $mysql_data['db_table_users'], 
            $_GET['id']
        );
		
		$db->query($query);
		$db->next_record();
		
		$user_id     = $db->f("id");
		$login       = $db->f("login");
		$email       = $db->f("email");
		$perms       = $db->f("permission_level");
		$status      = $db->f("active");
		
		$name        = $db->f("name");
		$surname     = $db->f("surname");
		$city        = $db->f("city");
		$country     = $db->f("country");
		
		$www         = $db->f("www");
		$gg          = $db->f("gg");
		$tlen        = $db->f("tlen");
		$jid         = $db->f("jid");
		
		$hobby       = $db->f("hobby");
		$adinfo      = $db->f("additional_info");
		
		$ft->assign(array(
            'USER_ID'           =>$user_id,
            
            'LOGIN'             =>$login,
            'EMAIL'             =>$email,
            
            'NAME'              =>$name,
            'SURNAME'           =>$surname,
            'CITY'              =>$city,
            'COUNTRY'           =>$country,
            
            'WWW'               =>$www,
            'GG'                =>$gg,
            'TLEN'              =>$tlen,
            'JID'               =>$jid,
            
            'HOBBY'             =>$hobby,
            'ADDITIONAL_INFO'   =>$adinfo,
            
            'SUBMIT_URL'		=>"main.php?p=13&amp;action=edit&amp;id=" . $user_id,
            'SUBMIT_HREF_DESC'	=>"zmodyfikuj dane u¿ytkownika",
            'HEADER_DESC'		=>"<b>U¿ytkownicy - modyfikacja u¿ytkownika</b>"
        ));

		$ft->define('form_useradd', "form_useradd.tpl");
		$ft->parse('ROWS',	".form_useradd");
		break;

	case "edit":

        // edycja wybranego wpisu
        $login      = $_POST['login_name'];
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
			
			$err = "Podaj poprawny adres e-mail.<br />";
		}
		
		if(!empty($err)) {
			
			$err .= "<br /><a href=\"javascript:history.back();\">powrót</a>";

			$ft->assign('CONFIRM', $err);
			$ft->parse('ROWS', ".result_note");
			
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
                    gg              = '%9\$d',
                    tlen            = '%10\$s', 
                    jid             = '%11\$s',
                    
                    hobby           = '%12\$s', 
                    additional_info = '%13\$s'
                WHERE 
                    id = '%14\$d'", 
		
                $mysql_data['db_table_users'], 
                
                $login, 
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
            $ft->assign('CONFIRM', "U¿ytkownik zosta³ zmodyfikowany.");
            $ft->parse('ROWS',	".result_note");
		}
		break;

	case "delete":// usuwanie wybranego wpisu
	
        $query = sprintf("
            SELECT 
                login
            FROM 
                %1\$s 
            WHERE 
                id = '%2\$d'",
        
            $mysql_data['db_table_users'], 
            $_GET['id']
        );
        
        $db->query($query);
		$db->next_record();
		
		$login = $db->f("login");
		
		if($login == $_SESSION['login']) {
		    
		    $ft->assign('CONFIRM', "Jeste¶ zalogowany jako u¿ytkownik, którego chcesz usun±æ. Operacja niedozwolona.");
		} else {
	
            $query = sprintf("
                DELETE FROM 
                    %1\$s 
                WHERE 
                    id = '%2\$d'", 
		
                $mysql_data['db_table_users'], 
                $_GET['id']
            );
            
            $db->query($query);
            
            $ft->assign('CONFIRM', "U¿ytkownik zosta³ usuniêty.");
		}
		$ft->parse('ROWS', ".result_note");
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
		    
                $mysql_data['db_table_users'],
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
                        
                    $ft->assign('CONFIRM', 'Nie mo¿esz zwiêkszyæ uprawnieñ poni¿ej 1.');
                } else {
                        
                    $p_level = $p_level-1;
                        
                    switch ($p_level) {
                            
                        case '3':
                            $new_permissions->permissions["read"]                  = TRUE;
                            $new_permissions->permissions["write"]                 = TRUE;
                            $new_permissions->permissions["delete"]                = TRUE;
                            break;
                                
                        case '2':
                            $new_permissions->permissions["read"]                  = TRUE;
                            $new_permissions->permissions["write"]                 = TRUE;
                            break;
                                
                        case '1':
                            $new_permissions->permissions["read"]                  = TRUE;
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
                        
                        $mysql_data['db_table_users'],
                        $mask,
                        $_GET['id']
                    );
                    
                    $db->query($query);
                        
                    $ft->assign('CONFIRM', 'Zmniejszono poziom uprawnieñ.');
                }
                 
             
            }
            
            if($plevel == "up") {
                
                if($p_level == 4) {
                        
                    $ft->assign('CONFIRM', 'Nie mo¿esz zwiêkszyæ uprawnieñ powy¿ej 4.');
                } else {
                    
                    $p_level = $p_level+1;
                        
                    switch ($p_level) {
                            
                        case '4':
                            $new_permissions->permissions["read"]                  = TRUE;
                            $new_permissions->permissions["write"]                 = TRUE;
                            $new_permissions->permissions["delete"]                = TRUE;
                            $new_permissions->permissions["edit_templates"]        = TRUE;
                            break;
                                
                        case '3':
                            $new_permissions->permissions["read"]                  = TRUE;
                            $new_permissions->permissions["write"]                 = TRUE;
                            $new_permissions->permissions["delete"]                = TRUE;
                            break;
                                
                        case '2':
                            $new_permissions->permissions["read"]                  = TRUE;
                            $new_permissions->permissions["write"]                 = TRUE;
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
                        
                        $mysql_data['db_table_users'],
                        $mask,
                        $_GET['id']
                    );
                    
                    $db->query($query);
                        
                    $ft->assign('CONFIRM', 'Zwiêkszono poziom uprawnieñ.');
                }
            }
            
		}
	
		$query = sprintf("
            SELECT * FROM 
                %1\$s 
            ORDER BY 
                id 
            ASC", 
		
            $mysql_data['db_table_users']
        );
        
		$db->query($query);
	
		// Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
		while($db->next_record()) {
		
			$user_id     = $db->f("id");
			$login       = $db->f("login");
			$email       = $db->f("email");
			$perm_level  = $db->f("permission_level");
			$status      = $db->f("active");
			
			switch ($perm_level) {
			    
			    case '1': $level = 1;
			    break;
			    
			    case '3': $level = 2;
			    break;
			    
			    case '7': $level = 3;
			    break;
			    
			    case '15':$level = 4;
			    break;
			    
			    case '31':$level = 5;
			    break;
			   
			}
			
			if($permarr["admin"]){
			        
                switch ($level) {
                    
                    case '1':
                    
                    $ft->assign(array(
                        'LEVEL_DOWN'    =>' ',
                        'LEVEL_UP'      =>'<a href="main.php?p=13&amp;plevel=up&amp;id=' . $user_id . '">+</a>'
                    ));
                    break;
                    
                    case '2':
                    case '3':
                    
                    
                    $ft->assign(array(
                        'LEVEL_DOWN'    =>'&nbsp;<a href="main.php?p=13&amp;plevel=down&amp;id=' . $user_id . '">-</a>',
                        'LEVEL_UP'      =>'<a href="main.php?p=13&amp;plevel=up&amp;id=' . $user_id . '">+</a>'
                    ));
                    break;
                    
                    case '4':
                    $ft->assign(array(
                        'LEVEL_DOWN'    =>'&nbsp;<a href="main.php?p=13&amp;plevel=down&amp;id=' . $user_id . '">-</a>',
                        'LEVEL_UP'      =>' '
                    ));
                    break;
                }
			    
			}
			
			$ft->assign(array(
                'USER_ID'   =>$user_id,
                'NAME'      =>$login,
                'EMAIL'     =>$email,
                'LEVEL'     =>$level,
			));
			
			if($status == 'Y') {
			    
			    $ft->assign('STATUS', 'Tak');
			} else {
			    
			    $ft->assign('STATUS', 'Nie');
			}
			
			// deklaracja zmiennej $idx1::color switcher
			$idx1 = empty($idx1) ? '' : $idx1;
			
			$idx1++;
			
			$ft->define("editlist_users", "editlist_users.tpl");
			$ft->define_dynamic("row", "editlist_users");
			
			// naprzemienne kolorowanie wierszy
			if (($idx1%2)==1) {
			    
			    $ft->assign('ID_CLASS', 'class="mainList"');
			    
			    $ft->parse('ROWS',	".row");
			} else {
			    
			    $ft->assign('ID_CLASS', 'class="mainListAlter"');
			    
			    $ft->parse('ROWS', ".row");
			}
		}
		$ft->parse('ROWS', "editlist_users");
		$ft->parse('ROWS', ".result_note");
}

?>