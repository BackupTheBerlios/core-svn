<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

// inicjalizacja instancji klasy DB_SQL
$db = new DB_SQL;

switch ($action) {
	case "add":
		class configValidate {
			
			var $monit = ""; // zmienna przechowuj±ca komunikaty b³êdów
			
			// metoda sprawdzaj±ca czy podana warto¶æ jest liczb± ca³kowit±
			function checkNumeric($v) {
				
				if(!is_numeric($v)) {
					
					$this->monit .= "Warto¶æ okre¶laj±ca liczbê postów musi byæ liczb± ca³kowit±.<br />";
					return FALSE;
				}
			}
			
			// metoda sprawdzaj±ca warto¶æ zmiennej pod wzglêdem
			// warto¶ci minimalnej i maksymalnej
			function checkValue($v, $min, $max, $desc) {
			
				if(($v < $min) || ($v > $max)) {
					
					$this->monit .= "Warto¶æ okre¶laj±ca liczbê postów " . $desc . " w musi byæ miêdzy " . $min . ", a " . $max . ".<br />";
					return FALSE;
				}
			}
		}
		
		// egzemplarz klasy validuj±cej ustawienia core
		/*
		$conf = new configValidate();
		
		$conf->checkNumeric($_POST['mainposts_per_page']);
		$conf->checkValue($_POST['mainposts_per_page'], 3, 10, "na stronie g³ównej");
		$conf->checkValue($_POST['editposts_per_page'], 15, 25, "w czê¶ci administracyjnej");
		*/
		
		if(empty($conf->monit)) {
		    
            // set {MAINPOSTS_PER_PAGE} variable
            // liczba listowanych wpisów w na stronie g³ównej::db
            $query = sprintf("
                UPDATE 
                    %1\$s 
				SET 
				    config_value = '%2\$d' 
				WHERE 
				    config_name = '%3\$s'",
            
                $mysql_data['db_table_config'], 
                $_POST['mainposts_per_page'], 
                'mainposts_per_page'
            );
            
            $db->query($query);
			
			// set {TITLE_PAGE} variable
			// liczba listowanych wpisów w na stronie g³ównej::db
			$query = sprintf("
                UPDATE 
                    %1\$s 
				SET 
				    config_value = '%2\$s' 
				WHERE 
				    config_name = '%3\$s'",
            
                $mysql_data['db_table_config'], 
                $_POST['title_page'], 
                'title_page'
            );
            
            $db->query($query);
		
			// set {EDITOSTS_PER_PAGE} variable
			// liczba listowanych wpisów w na stronie g³ównej::db
			$query = sprintf("
                UPDATE 
                    %1\$s 
				SET 
				    config_value = '%2\$d' 
				WHERE 
				    config_name = '%3\$s'",
            
                $mysql_data['db_table_config'], 
                $_POST['editposts_per_page'], 
                'editposts_per_page'
            );
            
            $db->query($query);
            
            // set {MAX_PHOTO_WIDTH} variable
            // maksymalna szerko¶æ zdjêcia do³±czonego do wpisu, 
            // jakie jest wy¶wietlane na stronie g³ównej::db
			$query = sprintf("
                UPDATE 
                    %1\$s 
				SET 
				    config_value = '%2\$d' 
				WHERE 
				    config_name = '%3\$s'",
            
                $mysql_data['db_table_config'], 
                $_POST['max_photo_width'], 
                'max_photo_width'
            );
            
            $db->query($query);
			
			$ft->assign('CONFIRM', "Dane zosta³y zmodyfikowane.");
			$ft->parse('ROWS', ".result_note");
		
		} else {
			
			$conf->monit .= "<br /><a href=\"javascript:history.back(-1);\">powrót</a>";
			
			$ft->assign('CONFIRM', $conf->monit);
			$ft->parse('ROWS', ".result_note");

		}
		break;

    default:
    
        // set {MAINPOSTS_PER_PAGE} variable
        // liczba listowanych wpisów w na stronie g³ównej::db
        $query = sprintf("
            SELECT 
                config_value 
            FROM 
                %1\$s 
            WHERE 
                config_name = '%2\$s'",
        
            $mysql_data['db_table_config'], 
            'mainposts_per_page'
        );
            
        $db->query($query);
        $db->next_record();
        
		$mainposts_per_page = $db->f("config_value");
		
		// set {EDITPOSTS_PER_PAGE} variable
		// liczba listowanych wpisów w czê¶ci administracyjnej::db
        $query = sprintf("
            SELECT 
                config_value 
            FROM 
                %1\$s 
            WHERE 
                config_name = '%2\$s'",
        
            $mysql_data['db_table_config'], 
            'editposts_per_page'
        );
		
		$db->query($query);
        $db->next_record();
        
		$editposts_per_page = $db->f("config_value");
		
		// set {TITLE_PAGE} variable
		// tytu³ strony, wy¶wietlany w miejscu title::db
        $query = sprintf("
            SELECT 
                config_value 
            FROM 
                %1\$s 
            WHERE 
                config_name = '%2\$s'",
        
            $mysql_data['db_table_config'], 
            'title_page'
        );
		
		$db->query($query);
        $db->next_record();
        
		$title_page = $db->f("config_value");
		
		// set {MAX_PHOTO_WIDTH} variable
		// maksymalna szerko¶æ zdjêcia do³±czonego do wpisu, 
		// jakie jest wy¶wietlane na stronie g³ównej::db
        $query = sprintf("
            SELECT 
                config_value 
            FROM 
                %1\$s 
            WHERE 
                config_name = '%2\$s'",
        
            $mysql_data['db_table_config'], 
            'max_photo_width'
        );
		
		$db->query($query);
        $db->next_record();
        
		$max_photo_width = $db->f("config_value");
		
		// Ustawiamy zmienne
        $ft->assign(array(
            'MAINPOSTS_PER_PAGE'    =>$mainposts_per_page,
            'EDITPOSTS_PER_PAGE'    =>$editposts_per_page,
            'TITLE_PAGE'            =>$title_page,
            'MAX_PHOTO_WIDTH'       =>$max_photo_width
        ));
			
		// w przypadku braku akcji wy¶wietlanie formularza
		$ft->define('form_configuration', "form_configuration.tpl");
		$ft->parse('ROWS', ".form_configuration");
}
?>