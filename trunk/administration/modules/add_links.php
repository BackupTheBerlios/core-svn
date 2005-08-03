<?php
// $Id$

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {
	case "add":
	
		$link_name	= trim($_POST['link_name']);
		$link_url	= trim($_POST['link_url']);

        if ( !preg_match('#^(http|ftp|https)://#i', $link_url) ) {

			$link_url = 'http://' . $link_url;
		}

		$monit = array();

		$ft->define("error_reporting", "error_reporting.tpl");
		$ft->define_dynamic("error_row", "error_reporting");

		if($permarr['moderator']) {

            // Obs³uga formularza, jesli go zatwierdzono
            if (strlen($link_name) <= 2) {

                $monit[] = $i18n['add_links'][0];
            }

            /*
             * TODO:
             * czy na pewno tak ? do msie jest plugin rozszerzajacy o 
             * linki: gg:IDGADUGADU
             * niedlugo do jabbera prawdopodobnie wejdzie protokol xmpp:
             * blokujesz takze mailto:
             * teraz nie pozwalamy na takie linki - dlaczego ?
             * nie sprawdzac niczego, poza tym czy cos w ogole jest wpisane.
             * jesli jest, to przechodzi
             *
             * zostawiam dla Twoich przemyslen :)
             *
             * poza tym - staraj sie uzywac wyrazen regularnych preg_*,
             * zamiast ereg*. sprobuj potestowac wydajbnosc jednych i drugich,
             * to zrozumiesz dlaczego :)
             *
             */
            if( !eregi("^(ftp|https?)://([-a-z0-9]+\.)+([a-z]{2,})$", $link_url) ) {
                
                $monit[] = $i18n['add_links'][1];
            }
            
            if(empty($monit)) {
                
                $query = sprintf("
                    SELECT 
                        max(link_order) as max_order 
                    FROM 
                        %1\$s",
        
                    TABLE_LINKS
                );
            
                $db->query($query);
                $db->next_record();
			
                // Przypisanie zmiennej $id
                $max_order = $db->f("max_order");
                
                $query = sprintf("
                    INSERT INTO 
                        %1\$s 
                    VALUES 
                        ('', '%2\$d', '%3\$s', '%4\$s')",
			
                    TABLE_LINKS, 
                    $max_order + 10, 
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
		
		$ft->define('form_linkadd', "form_linkadd.tpl");
		$ft->parse('ROWS', ".form_linkadd");
}

?>
