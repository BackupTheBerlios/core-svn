<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action)
{
	case "add":
		$link_name	= $_POST['link_name'];
		$link_url	= $_POST['link_url'];
			
		// egzemplarz klasy ³aduj±cej komentarz do bazy danych
		$db = new MySQL_DB;
			
		$db->query("INSERT INTO $mysql_data[db_table_links] VALUES ('', '$link_name', '$link_url')");
			
		$ft->assign('CONFIRM', "Link zosta³ dodany do bazy danych");
		$ft->parse('ROWS', ".result_note");
		break;

	default:
		// przydzielenie zmiennych::array
		$ft->assign(array(	'SUBMIT_URL'		=>"add,11,action.html",
							'LINK_VALUE'		=>"",
							'LINKURL_VALUE'		=>"value=\"http://\"",
							'SUBMIT_HREF_DESC'	=>"dodaj nowy link",
							'HEADER_DESC'		=>"<b>Linki - dodaj nowy link</b>"));
		
		// w przypadku braku akcji wy¶wietlanie formularza
		$ft->define('form_linkadd', "form_linkadd.tpl");
		$ft->parse('ROWS', ".form_linkadd");
}

?>
