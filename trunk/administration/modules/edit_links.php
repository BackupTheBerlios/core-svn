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
		
            TABLE_LINKS, 
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
			
                TABLE_LINKS, 
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
		
	case "remark": // zmiana pozycji wybranego linku
	
        if($permarr['moderator']) {
            
            $move = intval($_GET['move']);
	
            $query = sprintf("
                UPDATE 
                    %1\$s 
                SET 
                    link_order = link_order + '%2\$d' 
                WHERE 
                    id='%3\$d'", 
		
                TABLE_LINKS, 
                $move, 
                $_GET['id']
            );
		
            $db->query($query);
            
            // instancja potrzebna
            $sql = new DB_SQL;
            
            $query = sprintf("
                SELECT * FROM 
                    %1\$s 
                ORDER BY 
                    link_order 
                ASC", 
    
                TABLE_LINKS
            );
    
            $sql->query($query);
    
            $i = 10;
            $inc = 10;
    
            while($sql->next_record()) {
        
                $lid = $sql->f("id");
        
                $query = sprintf("
                    UPDATE 
                        %1\$s 
                    SET 
                        link_order = '$i' 
                    WHERE 
                        id = '$lid'", 
        
                    TABLE_LINKS
                );
                    
                $db->query($query);
                    
                $i += 10;
            }
            
            header("Location: main.php?p=12");
            exit;
		
        } else {
            
            $monit[] = $i18n['edit_category'][6];
            
            foreach ($monit as $error) {
                
                $ft->assign('ERROR_MONIT', $error);
                
                $ft->parse('ROWS',	".error_row");
            }
                        
            $ft->parse('ROWS', "error_reporting");
        }
		break;

	case "delete":// usuwanie wybranego wpisu
	
        // potwierdzenie usuniecia linku
        $confirm = empty($_POST['confirm']) ? '' : $_POST['confirm'];
        switch ($confirm) {
            
            case "Tak":
            
                $post_id = empty($_POST['post_id']) ? '' : $_POST['post_id'];
	
                if($permarr['moderator']) {
	
                    $query = sprintf("
                        DELETE FROM 
                            %1\$s 
                        WHERE 
                            id = '%2\$d'", 
		
                        TABLE_LINKS, 
                        $post_id
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
                                    
        case "Nie":
        
            header("Location: main.php?p=12");
            exit;
            break;
            
        default:
        
            $ft->define('confirm_action', 'confirm_action.tpl');
            $ft->assign(array(
                'PAGE_NUMBER'   =>$p, 
                'POST_ID'       =>$_GET['id']
            ));
            
            $ft->parse('ROWS', ".confirm_action");
            break;
        }
    break;
		
    case "multidelete": // usuwanie zaznaczonych linkow
	
        if($permarr['moderator']) {
            
            if(!empty($_POST['selected_links'])) {
                
                $query = sprintf("
                    DELETE FROM 
                        %1\$s 
                    WHERE 
                        id 
                    IN(".implode(',', $_POST['selected_links']).")", 
		
                    TABLE_LINKS
                );
		
                $db->query($query);
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
            SELECT 
                MIN(link_order) as min_order, 
                MAX(link_order) as max_order 
            FROM 
                %1\$s",
        
            TABLE_LINKS
        );
            
        $db->query($query);
        $db->next_record();
			
        // Przypisanie zmiennej $id
        $max_order = $db->f("max_order");
        $min_order = $db->f("min_order");
	
		$query = sprintf("
            SELECT * FROM 
                %1\$s 
            ORDER BY 
                link_order 
            ASC", 
		
            TABLE_LINKS
        );
		
		$db->query($query);
	
		// Sprawdzamy, czy w bazie danych s± ju¿ jakie¶ wpisy
		if($db->num_rows() > 0) {
		
		    // Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
            while($db->next_record()) {
		
                $link_id	= $db->f("id");
                $link_order = $db->f("link_order");
                $link_name	= $db->f("title");
                $link_url	= $db->f("url");
                
                $link_url = strlen($link_url) > 30 ? substr_replace($link_url, '...', 30) : $link_url;
			
                $ft->assign(array(
                    'LINK_ID'	=>$link_id,
                    'LINK_NAME'	=>$link_name,
                    'LINK_URL'	=>$link_url
                ));
                
                if($link_order == $max_order) {
                    // przydzielamy przycisk do podwy¿eszenia pozycji kategorii
                    $ft->assign(array(
                        'DOWN'  =>'',
                        'UP'    =>'<a href="main.php?p=12&amp;action=remark&amp;move=-15&amp;id=' . $link_id . '"><img src="templates/' . $lang . '/images/up.gif" width="11" height="7" /></a>'
                    ));
                } elseif ($link_order == $min_order) {
                    // przydzielamy przycisk do obnizenia pozycji kategorii
                    $ft->assign(array(
                        'DOWN'  =>'<a href="main.php?p=12&amp;action=remark&amp;move=15&amp;id=' . $link_id . '"><img src="templates/' . $lang . '/images/down.gif" width="11" height="7" /></a>', 
                        'UP'    =>''
                    ));
                } else {
                    // przydzielamy dwa przyciski do zmiany polozenia kategorii
                    $ft->assign(array(
                        'UP'    =>'<a href="main.php?p=12&amp;action=remark&amp;move=-15&amp;id=' . $link_id . '"><img src="templates/' . $lang . '/images/up.gif" width="11" height="7" /></a>', 
                        'DOWN'  =>'<a href="main.php?p=12&amp;action=remark&amp;move=15&amp;id=' . $link_id . '"><img src="templates/' . $lang . '/images/down.gif" width="11" height="7" /></a>'
                    ));
                }		
			
                // deklaracja zmiennej $idx1::color switcher
                $idx1 = empty($idx1) ? '' : $idx1;
			
                $idx1++;
			
                $ft->define("editlist_links", "editlist_links.tpl");
                $ft->define_dynamic("row", "editlist_links");
			
                // naprzemienne kolorowanie wierszy tabeli
				$ft->assign('ID_CLASS', $idx1%2 ? 'mainList' : 'mainListAlter');
				
				$ft->parse('ROW', ".row");
            }
            $ft->parse('ROWS', "editlist_links");
		} else {
		    
		    $ft->assign('CONFIRM', $i18n['edit_links'][8]);
			$ft->parse('ROWS',	".result_note");
		}
}

?>