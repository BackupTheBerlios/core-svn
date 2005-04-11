<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

$db = new DB_SQL;

switch ($action) {
	
	case "show": // wy¶wietlanie wpisu pobranego do modyfikacji
	
		$query = sprintf("
					SELECT * FROM 
						$mysql_data[db_table_category] 
					WHERE 
						category_id = '%1\$d'", $_GET['id']);
		
		$db->query($query);
		$db->next_record();
		
		$category_id			= $db->f("category_id");
		$category_name			= $db->f("category_name");
		$category_description	= $db->f("category_description");
		
		$category_description	= str_replace("<br />", "\r\n", $category_description);
		$category_description	= ereg_replace("(\r\n)+", "\r\n\r\n", $category_description);
		
		$ft->assign(array(	'CATEGORY_ID'		=>$category_id,
							'CATEGORY_NAME'		=>$category_name,
							'CATEGORY_DESC'		=>$category_description,
							'SUBMIT_URL'		=>"main.php?p=9&amp;action=edit&amp;id=" . $category_id,
							'CATNAME_VALUE'		=>"value=\"" . $category_name . "\"",
							'CATNAME_DESC'		=>$category_description,
							'SUBMIT_HREF_DESC'	=>"zmodyfikuj kategoriê",
							'HEADER_DESC'		=>"<b>Kategorie - modyfikacja istniej±cej kategorii</b>"));

		$ft->define('form_category', "form_category.tpl");
		$ft->parse('ROWS',	".form_category");
		break;
		
	case "edit":// edycja wybranego wpisu
	
		$category_description	= nl2br($_POST['category_description']);
		$category_name			= $_POST['category_name'];
		
		$query = sprintf("
					UPDATE 
						$mysql_data[db_table_category] 
					SET 
						category_name = '$category_name', 
						category_description = '$category_description' 
					WHERE 
						category_id='%1\$d'", $_GET['id']);
		
		$db->query($query);
		
		$ft->assign('CONFIRM', "Kategoria zosta³a zmodyfikowana.");
		$ft->parse('ROWS',	".result_note");
		break;
		
	case "delete":// usuwanie wybranego wpisu
	
		$query = sprintf("
					DELETE FROM 
						$mysql_data[db_table_category] 
					WHERE 
						category_id = '%1\$d'", $_GET['id']);
		
		$db->query($query);
		
		$ft->assign('CONFIRM', "Kategoria zosta³a usuniêta.");
		$ft->parse('ROWS', ".result_note");
		break;
		
	default:
	
		$query = "	SELECT 
						a.*, count(b.id) AS count 
					FROM 
						$mysql_data[db_table_category] a 
					LEFT JOIN 
						$mysql_data[db_table] b 
					ON 
						a.category_id = b.c_id 
					GROUP BY 
						category_id
					ORDER BY 
						category_id 
					ASC";
		
		$db->query($query);
	
		// Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
		while($db->next_record()) {
		
			$category_id			= $db->f("category_id");
			$category_name			= $db->f("category_name");
			$category_description	= $db->f("category_description");
			$count					= $db->f("count");
			
			if (strlen($category_description) > 70 ) {
				
				$category_description = substr_replace($category_description, '...',70);
			}
			
			$ft->assign(array(	'CATEGORY_ID'		=>$category_id,
								'CATEGORY_NAME'		=>$category_name,
								'COUNT'				=>$count));
								
			if(empty($category_description)) {

				$ft->assign('CATEGORY_DESC', "brak opisu");
			} else {
				
				$ft->assign('CATEGORY_DESC', $category_description);
			}	

			// deklaracja zmiennej $page_string::page switcher
			$page_string = empty($page_string) ? '' : $page_string;
				
			$ft->assign('STRING', $page_string);					
			
			// deklaracja zmiennej $idx1::color switcher
			$idx1 = empty($idx1) ? '' : $idx1;
			
			$idx1++;
			
			$ft->define("editlist_category", "editlist_category.tpl");
			$ft->define_dynamic("row", "editlist_category");
			
			// naprzemienne kolorowanie wierszy
			if (($idx1%2)==1) {
			    
			    $ft->assign('ID_CLASS', "class=\"mainList\"");
			    
			    $ft->parse('ROWS',	".row");
			} else {
			    
			    $ft->assign('ID_CLASS', "class=\"mainListAlter\"");
			    
			    $ft->parse('ROWS', ".row");
			}
		}
		
		$ft->parse('ROWS', "editlist_category");
}

?>