<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

if (empty($action)) {
	
	// przydzielenie zmiennych::array
	$ft->assign(array(	'SUBMIT_URL'		=>"note.addcategory",
						'CATNAME_VALUE'		=>"",
						'CATNAME_DESC'		=>"",
						'SUBMIT_HREF_DESC'	=>"dodaj now± kategoriê",
						'HEADER_DESC'		=>"<b>Kategorie - dodaj now± kategoriê</b>"));
	
	// w przypadku braku akcji wy¶wietlanie formularza
	$ft->parse('ROWS', ".form_category");
	
}

if($action == "add") {
		
	$category_name			= $_POST['category_name'];
	$category_description	= $_POST['category_description'];
		
	// egzemplarz klasy ³aduj±cej komentarz do bazy danych
	$d_base = new MySQL_DB;
		
	$d_base->query("INSERT INTO $mysql_data[db_table_category] VALUES ('', '$category_name', '$category_description')");
	$d_base->next_record();
		
	$ft->assign('CONFIRM', "Kategoria zosta³a dodana do bazy danych");
	$ft->parse('ROWS', ".result_note");
	
}
?>