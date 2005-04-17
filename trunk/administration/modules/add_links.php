<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {
	case "add":
	
		$link_name	= $_POST['link_name'];
		$link_url	= $_POST['link_url'];

		if(	substr($link_url, 0, 7) != 'http://' && 
			substr($link_url, 0, 6) != 'ftp://' && 
			substr($link_url, 0, 8) != 'https://') {
				
			$link_url = 'http://' . $link_url;
		}
		
		$monit = array();
		
		$ft->define("error_reporting", "error_reporting.tpl");
		$ft->define_dynamic("error_row", "error_reporting");
		
		if($permarr['moderator']) {
	
            // Obs�uga formularza, jesli go zatwierdzono
            if(!eregi("^([^0-9]+){2,}$", $link_name)) {
                
                $monit[] = $i18n['add_links'][0];
            }
            
            if(!eregi("^(www|ftp|http)://([-a-z0-9]+\.)+([a-z]{2,})$", $link_url)) {
                
                $monit[] = $i18n['add_links'][1];
            }
            
            if(empty($monit)) {
                
                $query = sprintf("
                    INSERT INTO 
                        %1\$s 
                    VALUES 
                        ('', '%2\$s', '%3\$s')",
			
                    $mysql_data['db_table_links'],
                    $link_name,
                    $link_url
                );
		
                $db->query($query);
			
                $ft->assign('CONFIRM', $i18n['add_links'][2]);
                $ft->parse('ROWS', ".result_note");
            } else {
                
                foreach ($monit as $error) {
    
                    $ft->assign('ERROR_MONIT', $error);
                    
                    $ft->parse('ROWS',	".error_row");
                }
                        
                $ft->parse('ROWS', "error_reporting");
		  }
		} else {
		    
		    $monit[] = $i18n['add_links'][5];
		    
		    foreach ($monit as $error) {
		        
		        $ft->assign('ERROR_MONIT', $error);
		        
		        $ft->parse('ROWS',	".error_row");
		    }
		    
		    $ft->parse('ROWS', "error_reporting");
		}
		
		break;

	default:
	
		// przydzielenie zmiennych::array
		$ft->assign(array(
            'SUBMIT_URL'		=>"main.php?p=11&amp;action=add",
            'LINK_VALUE'		=>"",
            'LINKURL_VALUE'		=>"value=\"http://\"",
            'SUBMIT_HREF_DESC'	=>$i18n['add_links'][3],
            'HEADER_DESC'		=>$i18n['add_links'][4]
		));
		
		// w przypadku braku akcji wy�wietlanie formularza
		$ft->define('form_linkadd', "form_linkadd.tpl");
		$ft->parse('ROWS', ".form_linkadd");
}

?>