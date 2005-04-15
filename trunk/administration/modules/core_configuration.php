<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {
    
	case "add":

        $mainposts_per_page = $_POST['mainposts_per_page'];
        $editposts_per_page = $_POST['editposts_per_page'];
        $max_photo_width    = $_POST['max_photo_width'];
        
        $monit = array();
        
        //$monit[] = !is_numeric($mainposts_per_page) ? $i18n['core_configuration'][0] : '';
        //$monit[] = !is_numeric($editposts_per_page) ? $i18n['core_configuration'][2] : '';
        
        //$monit[] = !is_numeric($max_photo_width) ? $i18n['core_configuration'][4] : '';
        
		if(!is_numeric($mainposts_per_page)) $monit[] = $i18n['core_configuration'][0];
		if(!is_numeric($editposts_per_page)) $monit[] = $i18n['core_configuration'][2];
		
		if(!is_numeric($max_photo_width)) $monit[] = $i18n['core_configuration'][4];
		
		if(($mainposts_per_page < 3) || ($mainposts_per_page > 10)) $monit[] = $i18n['core_configuration'][1];
        if(($editposts_per_page < 10) || ($editposts_per_page > 20)) $monit[] = $i18n['core_configuration'][3];
		
		if(empty($monit)) {
		    
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
			
			$ft->assign('CONFIRM', $i18n['core_configuration'][5]);
			$ft->parse('ROWS', ".result_note");
		
		} else {
			
			$ft->define("error_reporting", "error_reporting.tpl");
            $ft->define_dynamic("error_row", "error_reporting");

            foreach ($monit as $error) {
    
                $ft->assign('ERROR_MONIT', $error);
                    
                $ft->parse('ROWS',	".error_row");
            }
                        
            $ft->parse('ROWS', "error_reporting");

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