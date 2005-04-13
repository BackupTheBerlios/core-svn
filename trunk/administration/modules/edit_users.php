<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

$db = new DB_SQL;

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
		
		$user_id		= $db->f("id");
		$user_name		= $db->f("login");
		$user_email		= $db->f("email");
		$user_status	= $db->f("active");
		
		$ft->assign(array(
            'USER_ID'			=>$user_id,
            'USER_NAME'			=>$user_name,
            'LINK_EMAIL'		=>$user_email,
            'SUBMIT_URL'		=>"main.php?p=13&amp;action=edit&amp;id=" . $user_id,
            'LINK_VALUE'		=>"value=\"" . $user_name . "\"",
            'LINKEMAIL_VALUE'	=>"value=\"" . $user_email . "\"",
            'SUBMIT_HREF_DESC'	=>"zmodyfikuj dane u¿ytkownika",
            'HEADER_DESC'		=>"<b>U¿ytkownicy - modyfikacja u¿ytkownika</b>"
        ));

		$ft->define('form_useradd', "form_useradd.tpl");
		$ft->parse('ROWS',	".form_useradd");
		break;

	case "edit":

        // edycja wybranego wpisu
        $user_name	= $_POST['login_name'];
        $user_email	= $_POST['email'];
        
        if(!check_mail($user_email)){
			
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
                    login	= '%2\$s', 
                    email	= '%3\$s' 
                WHERE 
                    id = '%4\$d'", 
		
                $mysql_data['db_table_users'], 
                $user_name, 
                $user_email, 
                $_GET['id']
            );
            
            $db->query($query);
            $ft->assign('CONFIRM', "U¿ytkownik zosta³ zmodyfikowany.");
            $ft->parse('ROWS',	".result_note");
		}
		break;

	case "delete":// usuwanie wybranego wpisu
	
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
		$ft->parse('ROWS', ".result_note");
		break;

	default:
	
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
			
			$ft->define("editlist_users", "editlist_users.tpl");
			$ft->define_dynamic("row", "editlist_users");
			
			// naprzemienne kolorowanie wierszy
			if (($idx1%2)==1) {
			    
			    $ft->assign('ID_CLASS', "class=\"mainList\"");
			    
			    $ft->parse('ROWS',	".row");
			} else {
			    
			    $ft->assign('ID_CLASS', "class=\"mainListAlter\"");
			    
			    $ft->parse('ROWS', ".row");
			}
		}
		$ft->parse('ROWS', "editlist_users");
}

?>