<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

if (empty($action)) {
	
	$data_base = new MySQL_DB;
	$data_base->query("	SELECT a.*, count(b.id) AS count 
						FROM $mysql_data[db_table_category] a 
						LEFT JOIN $mysql_data[db_table] b 
						ON a.category_id = b.c_id 
						GROUP BY category_id
						ORDER BY category_id ASC");
	
	
	// Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
	while($data_base->next_record()) {
	
		$category_id			= $data_base->f("category_id");
		$category_name			= $data_base->f("category_name");
		$category_description	= $data_base->f("category_description");
		$count					= $data_base->f("count");
		
		if (strlen($category_description) > 70 ) {
			
			$category_description = substr_replace($category_description, '...',70);
		} else {
			$category_description = $category_description;
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
		
		// naprzemienne kolorowanie wierszy tabeli
		if (($idx1%2)==1) {
			
			$ft->assign('ID_CLASS', "id=\"mainList\"");
			// parsowanie szablonów
			$ft->parse('NOTE_ROWS',	".table_categorylist");
		} else {
			
			$ft->assign('ID_CLASS', "id=\"mainListAlter\"");
			$ft->parse('NOTE_ROWS',	".table_categorylist");
		}
	}
	
	$ft->parse('ROWS',	".header_categorylist");
}

// wy¶wietlanie wpisu pobranego do modyfikacji
if ($action == "show") {
	
	$db_base = new MySQL_DB;
	$db_base->query("SELECT * FROM $mysql_data[db_table_category] WHERE category_id = '$_GET[id]'");
	$db_base->next_record();
	
	$category_id			= $db_base->f("category_id");
	$category_name			= $db_base->f("category_name");
	$category_description	= $db_base->f("category_description");
	
	$category_description	= str_replace("<br />", "\r\n", $category_description);
	$category_description	= ereg_replace("(\r\n)+", "\r\n\r\n", $category_description);
	
	$ft->assign(array(	'CATEGORY_ID'		=>$category_id,
						'CATEGORY_NAME'		=>$category_name,
						'CATEGORY_DESC'		=>$category_description,
						'SUBMIT_URL'		=>"category.edit." . $category_id,
						'CATNAME_VALUE'		=>"value=\"" . $category_name . "\"",
						'CATNAME_DESC'		=>$category_description,
						'SUBMIT_HREF_DESC'	=>"zmodyfikuj kategoriê",
						'HEADER_DESC'		=>"<b>Kategorie - modyfikacja istniej±cej kategorii</b>"));

	$ft->parse('ROWS',	".form_category");
	
}

// edycja wybranego wpisu
if ($action == "edit") {
	
	$category_description	= nl2br($_POST['category_description']);
	$category_name			= $_POST['category_name'];
	
	$d_base = new MySQL_DB;
	$d_base->query(	"UPDATE $mysql_data[db_table_category] SET category_name='$category_name', category_description='$category_description' WHERE category_id='$_GET[id]'");
	$d_base->next_record();
	
	$ft->assign(array(	'CONFIRM'	=>"Kategoria zosta³a zmodyfikowana."));

	$ft->parse('ROWS',	".result_note");
	
}

// usuwanie wybranego wpisu
if ($action == "delete") {
	
	$d_base = new MySQL_DB;
	$d_base->query("DELETE FROM $mysql_data[db_table_category] WHERE category_id='$_GET[id]'");
	$d_base->next_record();
	
	$ft->assign(array(	'CONFIRM'	=>"Kategoria zosta³a usuniêta."));

	$ft->parse('ROWS', ".result_note");
	
}
?>