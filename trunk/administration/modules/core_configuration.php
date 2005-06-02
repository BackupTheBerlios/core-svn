<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {
    
	case "add":

        $mainposts_per_page = $_POST['mainposts_per_page'];
        $editposts_per_page = $_POST['editposts_per_page'];
        $max_photo_width    = $_POST['max_photo_width'];
        $rewrite_allow      = $_POST['rewrite_allow'];
        
        $monit = array();
        
        // definicja szablonow obslugujacych bledy Core.
        $ft->define("error_reporting", "error_reporting.tpl");
        $ft->define_dynamic("error_row", "error_reporting");
        
        if($permarr['admin']) {
        
            /*
             * $monit[] = !is_numeric($mainposts_per_page) ? $i18n['core_configuration'][0] : '';
             * $monit[] = !is_numeric($editposts_per_page) ? $i18n['core_configuration'][2] : '';
             *        
             * $monit[] = !is_numeric($max_photo_width) ? $i18n['core_configuration'][4] : '';
             */
            
            if(!is_numeric($mainposts_per_page)) $monit[] = $i18n['core_configuration'][0];
            if(!is_numeric($editposts_per_page)) $monit[] = $i18n['core_configuration'][2];
            
            if(!is_numeric($max_photo_width)) $monit[] = $i18n['core_configuration'][4];
            
            if(($mainposts_per_page < 3) || ($mainposts_per_page > 10)) $monit[] = $i18n['core_configuration'][1];
            if(($editposts_per_page < 10) || ($editposts_per_page > 20)) $monit[] = $i18n['core_configuration'][3];
            
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
                
                // set {MOD_REWRITE} variable
                set_config('mod_rewrite', $_POST['rewrite_allow']);

                // set {DATE_FORMAT} variable
                set_config('date_format', $_POST['date_format']);
                
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
    
		if(get_config('mod_rewrite') == 1) {

			$ft->assign('REWRITE_YES', 'checked="checked"');
		} else {
			
			$ft->assign('REWRITE_NO', 'checked="checked"');
		}
		
		// Ustawiamy zmienne
        $ft->assign(array(
            'MAINPOSTS_PER_PAGE'    =>get_config('mainposts_per_page'),
            'EDITPOSTS_PER_PAGE'    =>get_config('editposts_per_page'),
            'TITLE_PAGE'            =>get_config('title_page'),
            'MAX_PHOTO_WIDTH'       =>get_config('max_photo_width'),
            'DATE_FORMAT'           =>get_config('date_format')
        ));
			
		// w przypadku braku akcji wy¶wietlanie formularza
		$ft->define('form_configuration', "form_configuration.tpl");
		$ft->parse('ROWS', ".form_configuration");
}

?>
