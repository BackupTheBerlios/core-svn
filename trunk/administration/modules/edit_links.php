<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

$ft->define("error_reporting", "error_reporting.tpl");
$ft->define_dynamic("error_row", "error_reporting");

switch ($action) {
	
	case "show": // wy¶wietlanie wpisu pobranego do modyfikacji
	
		$query = sprintf("
            SELECT * FROM 
                %1\$s 
            WHERE 
                id = '%2\$d'", 
		
            $mysql_data['db_table_links'], 
            $_GET['id']
        );
		
		$db->query($query);
		$db->next_record();
		
		$link_id	= $db->f("id");
		$link_name	= $db->f("title");
		$link_url	= $db->f("url");
		
		$ft->assign(array(
            'LINK_ID'			=>$link_id,
            'LINK_NAME'			=>$link_name,
            'LINK_URL'			=>$link_url,
            'SUBMIT_URL'		=>"main.php?p=12&amp;action=edit&amp;id=" . $link_id,
            'LINK_VALUE'		=>"value=\"" . $link_name . "\"",
            'LINKURL_VALUE'		=>"value=\"" . $link_url . "\"",
            'SUBMIT_HREF_DESC'	=>$i18n['edit_links'][0],
            'HEADER_DESC'		=>$i18n['edit_links'][1]
		));

		$ft->define('form_linkadd', "form_linkadd.tpl");
		$ft->parse('ROWS',	".form_linkadd");
		break;

	case "edit":// edycja wybranego wpisu
	
        if($permarr['moderator']) {
	
            $link_name	= $_POST['link_name'];
            $link_url	= $_POST['link_url'];

            if(	substr($link_url, 0, 7) != 'http://' && 
                substr($link_url, 0, 6) != 'ftp://' && 
                substr($link_url, 0, 8) != 'https://') {
                    
                    $link_url = 'http://' . $link_url;
            }
		
            $monit = array();
	
            // Obs³uga formularza, jesli go zatwierdzono
            if(!eregi("^([^0-9]+){2,}$", $link_name)) {
                
                $monit[] = $i18n['edit_links'][2];
            }
            
            if(!eregi("^(www|ftp|http)://([-a-z0-9]+\.)+([a-z]{2,})$", $link_url)) {
                
                $monit[] = $i18n['edit_links'][3];
            }
		
            if(empty($monit)) {
            
                $query = sprintf("
                    UPDATE 
                        %1\$s 
                    SET 
                        title	= '%2\$s', 
                        url		= '%3\$s' 
                    WHERE 
                        id = '%4\$d'", 
			
                $mysql_data['db_table_links'], 
                $link_name, 
                $link_url, 
                $_GET['id']);
			
                $db->query($query);
		
                $ft->assign('CONFIRM', $i18n['edit_links'][4]);
                $ft->parse('ROWS',	".result_note");
            } else {
                
                foreach ($monit as $error) {
			    
                    $ft->assign('ERROR_MONIT', $error);
                    
                    $ft->parse('ROWS',	".error_row");
                }
                
                $ft->parse('ROWS', "error_reporting");
            }
        } else {
            
            $monit[] = $i18n['edit_links'][7];
            
            foreach ($monit as $error) {
			    
			    $ft->assign('ERROR_MONIT', $error);
			    
			    $ft->parse('ROWS',	".error_row");
			}
			
			$ft->parse('ROWS', "error_reporting");
        }
		
		break;

	case "delete":// usuwanie wybranego wpisu
	
        if($permarr['moderator']) {
	
            $query = sprintf("
                DELETE FROM 
                    %1\$s 
                WHERE 
                    id = '%2\$d'", 
		
                $mysql_data['db_table_links'], 
                $_GET['id']
            );
		
            $db->query($query);
		
            $ft->assign('CONFIRM', $i18n['edit_links'][5]);
            $ft->parse('ROWS', ".result_note");
        } else {
            
            $monit[] = $i18n['edit_links'][6];
            
            foreach ($monit as $error) {
			    
			    $ft->assign('ERROR_MONIT', $error);
			    
			    $ft->parse('ROWS',	".error_row");
			}
			
			$ft->parse('ROWS', "error_reporting");
        }
		break;
		
    case "multidelete": // usuwanie wybranego wpisu
	
        if($permarr['moderator']) {
            
            if(!empty($_POST['selected_links'])) {
            
                foreach($_POST['selected_links'] as $link_id) {
	
                    $query = sprintf("
                        DELETE FROM 
                            %1\$s 
                        WHERE 
                            id = '%2\$d'", 
		
                        $mysql_data['db_table_links'], 
                        $link_id
                    );
		
                    $db->query($query);
                }
		
                $ft->assign('CONFIRM', 'Linki zosta³y usuniête.');
            } else {
                $ft->assign('CONFIRM', 'Nie zaznaczono ¿adnych linków.');
                
            }
            
            $ft->parse('ROWS', ".result_note");
        } else {
            
            $monit[] = $i18n['edit_note'][2];

            foreach ($monit as $error) {
    
                $ft->assign('ERROR_MONIT', $error);
                    
                $ft->parse('ROWS',	".error_row");
            }
                        
            $ft->parse('ROWS', "error_reporting");
        }
		break;

	default:
	
		$query = sprintf("
            SELECT * FROM 
                %1\$s 
            ORDER BY 
                id 
            ASC", 
		
            $mysql_data['db_table_links']
        );
		
		$db->query($query);
	
		// Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
		while($db->next_record()) {
		
			$link_id	= $db->f("id");
			$link_name	= $db->f("title");
			$link_url	= $db->f("url");
			
			$link_url = strlen($link_url) > 30 ? substr_replace($link_url, '...', 30) : $link_url;
			
			$ft->assign(array(
                'LINK_ID'	=>$link_id,
                'LINK_NAME'	=>$link_name,
                'LINK_URL'	=>$link_url
            ));			
			
			// deklaracja zmiennej $idx1::color switcher
			$idx1 = empty($idx1) ? '' : $idx1;
			
			$idx1++;
			
			$ft->define("editlist_links", "editlist_links.tpl");
			$ft->define_dynamic("row", "editlist_links");
			
			// naprzemienne kolorowanie wierszy
			if (($idx1%2)==1) {
			    
			    $ft->assign('ID_CLASS', 'mainList');
			    
			    $ft->parse('ROWS',	".row");
			} else {
			    
			    $ft->assign('ID_CLASS', 'mainListAlter');
			    
			    $ft->parse('ROWS', ".row");
			}
		}
		$ft->parse('ROWS', "editlist_links");
}

?>