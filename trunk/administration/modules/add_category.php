<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action)
{
	case "add":
		$category_name			= $_POST['category_name'];
		$category_description	= $_POST['category_description'];
			
		// egzemplarz klasy ³aduj±cej komentarz do bazy danych
		$db = new MySQL_DB;
			
		$db->query("INSERT INTO $mysql_data[db_table_category] VALUES ('', '$category_name', '$category_description')");
			
		$ft->assign('CONFIRM', "Kategoria zosta³a dodana do bazy danych");
		$ft->parse('ROWS', ".result_note");
		break;

	default:
		// przydzielenie zmiennych::array
		$ft->assign(array(	'SUBMIT_URL'		=>"add,8,action.html",
							'CATNAME_VALUE'		=>"",
							'CATNAME_DESC'		=>"",
							'SUBMIT_HREF_DESC'	=>"dodaj now± kategoriê",
							'HEADER_DESC'		=>"<b>Kategorie - dodaj now± kategoriê</b>"));
		
		// w przypadku braku akcji wy¶wietlanie formularza
		$ft->parse('ROWS', ".form_category");
}

?>
