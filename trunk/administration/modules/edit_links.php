<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

$db = new MySQL_DB;

switch ($action) {
	case "show": // wy¶wietlanie wpisu pobranego do modyfikacji
	
		$query = "	SELECT * FROM 
						$mysql_data[db_table_links] 
					WHERE 
						id = '$_GET[id]'";
		
		$db->query($query);
		$db->next_record();
		
		$link_id	= $db->f("id");
		$link_name	= $db->f("title");
		$link_url	= $db->f("url");
		
		$ft->assign(array(	
						'LINK_ID'			=>$link_id,
						'LINK_NAME'			=>$link_name,
						'LINK_URL'			=>$link_url,
						'SUBMIT_URL'		=>"edit," . $link_id . ",12,edit.html",
						'LINK_VALUE'		=>"value=\"" . $link_name . "\"",
						'LINKURL_VALUE'		=>"value=\"" . $link_url . "\"",
						'SUBMIT_HREF_DESC'	=>"zmodyfikuj link",
						'HEADER_DESC'		=>"<b>Linki - modyfikacja linku</b>"
		));

		$ft->define('form_linkadd', "form_linkadd.tpl");
		$ft->parse('ROWS',	".form_linkadd");
		break;

	case "edit":// edycja wybranego wpisu
		$link_name	= $_POST['link_name'];
		$link_url	= $_POST['link_url'];

		if(	substr($link_url, 0, 7) != 'http://' && 
			substr($link_url, 0, 6) != 'ftp://' && 
			substr($link_url, 0, 8) != 'https://') {
				
			$link_url = 'http://' . $link_url;
		}
		
		$monit = empty($monit) ? '' : $monit;
	
		// Obs³uga formularza, jesli go zatwierdzono
		if(!eregi("^([^0-9]+){2,}$", $link_name)) {
			
			$monit .= "Musisz podaæ nazwê linku.<br />";
		}
		
		if(!eregi("^(www|ftp|http)://([-a-z0-9]+\.)+([a-z]{2,})$", $link_url)) {
			
			$monit .= "Link musi byæ w poprawnym formacie (www|ftp|http)://example.com<br />";
		}
		
		if(empty($monit)) {
		
			$query = "	UPDATE 
							$mysql_data[db_table_links] 
						SET 
							title	= '$link_name', 
							url		= '$link_url' 
						WHERE 
							id = '$_GET[id]'";
			
			$db->query($query);
		
			$ft->assign(array(	'CONFIRM'	=>"Link zosta³ zmodyfikowany."));
		} else {
			
			$monit .= "<br /><a href=\"javascript:history.back(-1);\">powrót</a>";
			$ft->assign('CONFIRM', $monit);
		}

		$ft->parse('ROWS',	".result_note");
		break;

	case "delete":// usuwanie wybranego wpisu
		$db->query("DELETE FROM 
						$mysql_data[db_table_links] 
					WHERE 
						id = '$_GET[id]'");
		
		$ft->assign('CONFIRM', "Link zosta³ usuniêty.");
		$ft->parse('ROWS', ".result_note");
		break;

	default:
		$query = "	SELECT * FROM 
						$mysql_data[db_table_links] 
					ORDER BY 
						id 
					ASC";
		
		$db->query($query);
	
		// Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
		while($db->next_record()) {
		
			$link_id	= $db->f("id");
			$link_name	= $db->f("title");
			$link_url	= $db->f("url");
			
			if (strlen($link_url) > 30 ) {
				
				$link_url = substr_replace($link_url, '...', 30);
			}
			
			$ft->assign(array(	'LINK_ID'	=>$link_id,
								'LINK_NAME'	=>$link_name,
								'LINK_URL'	=>$link_url));			
			
			// deklaracja zmiennej $idx1::color switcher
			$idx1 = empty($idx1) ? '' : $idx1;
			
			$idx1++;
			
			$ft->define('table_linkslist', "table_linkslist.tpl");
			// naprzemienne kolorowanie wierszy tabeli
			if (($idx1%2)==1) {
				
				$ft->assign('ID_CLASS', "class=\"mainList\"");
				// parsowanie szablonów
				$ft->parse('NOTE_ROWS',	".table_linkslist");
			} else {
				
				$ft->assign('ID_CLASS', "class=\"mainListAlter\"");
				$ft->parse('NOTE_ROWS',	".table_linkslist");
			}
		}
		$ft->define('header_linkslist', "header_linkslist.tpl");
		$ft->parse('ROWS',	".header_linkslist");
}

?>