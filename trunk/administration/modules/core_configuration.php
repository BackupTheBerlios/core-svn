<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {
    
	case "add":

        $mainposts_per_page = $_POST['mainposts_per_page'];
        $editposts_per_page = $_POST['editposts_per_page'];
        $max_photo_width    = $_POST['max_photo_width'];
        $rewrite_allow      = $_POST['rewrite_allow'];
        $start_page         = explode('#', $_POST['start_page']);
        
        $monit = array();
        
        // definicja szablonow obslugujacych bledy Core.
        $ft->define("error_reporting", "error_reporting.tpl");
        $ft->define_dynamic("error_row", "error_reporting");
        
        if($permarr['admin']) {
            
            if(!is_numeric($mainposts_per_page)) $monit[] = $i18n['core_configuration'][0];
            if(!is_numeric($editposts_per_page)) $monit[] = $i18n['core_configuration'][2];
            
            if(!is_numeric($max_photo_width)) $monit[] = $i18n['core_configuration'][4];
            
            if(($mainposts_per_page < 1)) $monit[] = $i18n['core_configuration'][1];
            if(($editposts_per_page < 1)) $monit[] = $i18n['core_configuration'][3];
            
            if(empty($monit)) {
		    
                // set {MAINPOSTS_PER_PAGE} variable
                // liczba listowanych wpisów w na stronie g³ównej::db
                set_config('mainposts_per_page', $_POST['mainposts_per_page']);
                
                // set {TITLE_PAGE} variable
                // liczba listowanych wpisów w na stronie g³ównej::db
                set_config('title_page', $_POST['title_page']);
                
                // set {EDITOSTS_PER_PAGE} variable
                // liczba listowanych wpisów w na stronie g³ównej::db
                set_config('editposts_per_page', $_POST['editposts_per_page']);
            
                // set {MAX_PHOTO_WIDTH} variable
                // maksymalna szerko¶æ zdjêcia do³±czonego do wpisu, 
                // jakie jest wy¶wietlane na stronie g³ównej::db
                set_config('max_photo_width', $_POST['max_photo_width']);
                
                // set {SHOW_CALENDAR} variable
                set_config('show_calendar', $_POST['show_calendar']);
                
                // set {MOD_REWRITE} variable
                set_config('mod_rewrite', $_POST['rewrite_allow']);

                // set {DATE_FORMAT} variable
                set_config('date_format', $_POST['date_format']);

                set_config('start_page_type', $start_page[0]);
                set_config('start_page_id', $start_page[1]);
                
                $ft->assign('CONFIRM', $i18n['core_configuration'][5]);
                $ft->parse('ROWS', ".result_note");
            
            } else {
                
                foreach ($monit as $error) {
    
                    $ft->assign('ERROR_MONIT', $error);
                    
                    $ft->parse('ROWS',	".error_row");
                }
                        
                $ft->parse('ROWS', "error_reporting");
            }
        } else {
            
            $monit[] = $i18n['core_configuration'][6];

            foreach ($monit as $error) {
    
                $ft->assign('ERROR_MONIT', $error);
                    
                $ft->parse('ROWS',	".error_row");
            }
                        
            $ft->parse('ROWS', "error_reporting");
            
        }
		break;

    default:
    
        $ft->assign(array(
            (bool)get_config('mod_rewrite') ? 'REWRITE_YES' : 'REWRITE_NO'      =>'checked="checked"', 
            (bool)get_config('show_calendar') ? 'CALENDAR_YES' : 'CALENDAR_NO'  =>'checked="checked"'
        ));
        
        // w przypadku braku akcji wy¶wietlanie formularza
		$ft->define('form_configuration', "form_configuration.tpl");
        
        $start_page_type    = get_config('start_page_type');
        $start_page_id      = get_config('start_page_id');

        $query = sprintf("
            SELECT 
                id,
                parent_id,
                title 
            FROM 
                %1\$s 
            WHERE 
                published = 'Y' 
            AND 
                parent_id = '%2\$d' 
            ORDER BY 
                id 
            ASC", 
	
            TABLE_PAGES,
            0
        );
	
        $db->query($query);

        if ((bool)$db->nf()) {
            $ft->define_dynamic('page_row', 'form_configuration');
            $ft->assign('START_PAGE_PAGES', true);
            $selected_start_id = $start_page_type=='page' ? $start_page_id : 0;

            while($db->next_record()) {
          
                $page_id      = $db->f("id");
                $parent_id    = $db->f("parent_id");
                $title        = $db->f("title");
              
                $ft->assign(array(
                    'P_ID'		    =>'page#' . $page_id,
                    'P_NAME'	    =>$title,
                    'CURRENT'       =>$page_id==$selected_start_id ? 'selected="selected"' : ''
                ));
          
                $ft->parse('PAGE_ROW', ".page_row");
          
                get_addpage_cat($page_id, 2, $selected_start_id, 'page#');
            }
        } else {
            $ft->assign('START_PAGE_PAGES', false);
        }



        $query = sprintf("
            SELECT 
                category_id, 
                category_parent_id,
                category_name 
            FROM 
                %1\$s 
            WHERE 
                category_parent_id = '%2\$d' 
            ORDER BY 
                category_id 
            ASC", 
	
            TABLE_CATEGORY,
            0
        );
	
        $db->query($query);
        if ((bool)$db->nf()) {
            $ft->define_dynamic('category_row', 'form_configuration');
            $ft->assign('START_PAGE_CATEGORIES', true);
            $selected_start_id = $start_page_type == 'cat' ? $start_page_id : 0;
	
            while($db->next_record()) {
		
                $category_id        = $db->f("category_id");
                $category_parent_id = $db->f("category_parent_id");
                $category_name      = $db->f("category_name");
            
                $ft->assign(array(
                    'C_ID'		    =>'cat#' . $category_id,
                    'C_NAME'	    =>$category_name,
                    'CURRENT'       =>$category_id==$selected_start_id ? 'selected="selected"' : ''
                ));
        
                $ft->parse('CATEGORY_ROW', ".category_row");
        
                get_addcategory_cat($category_id, 2, $selected_start_id, 'cat#');
            }

        } else {
            $ft->assign('START_PAGE_CATEGORIES', false);
        }
		
		// Ustawiamy zmienne
        $ft->assign(array(
            'MAINPOSTS_PER_PAGE'    =>get_config('mainposts_per_page'),
            'EDITPOSTS_PER_PAGE'    =>get_config('editposts_per_page'),
            'TITLE_PAGE'            =>get_config('title_page'),
            'MAX_PHOTO_WIDTH'       =>get_config('max_photo_width'),
            'DATE_FORMAT'           =>get_config('date_format')
        ));
			
		$ft->parse('ROWS', "form_configuration");
}

?>