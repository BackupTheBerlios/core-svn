<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {
	
	case "add":
	
		$category_name			= $_POST['category_name'];
		$category_description	= $_POST['category_description'];
		
		$monit = empty($monit) ? '' : $monit;
	
		// Obs�uga formularza, jesli go zatwierdzono
		if(!eregi("^([^0-9]+){2,}$", $category_name)) {
			
			$monit .= "Musisz poda� nazw� kategorii.<br />";
		}
		
		if(empty($monit)) {
			
			// egzemplarz klasy �aduj�cej komentarz do bazy danych
			$db = new DB_SQL;
			
			$query = "	INSERT INTO 
							$mysql_data[db_table_category] 
						VALUES 
							('', '$category_name', '$category_description')";
		
			$db->query($query);
			
			$ft->assign('CONFIRM', "Kategoria zosta�a dodana do bazy danych");
		} else {
			
			$monit .= "<br /><a href=\"javascript:history.back(-1);\">powr�t</a>";
			$ft->assign('CONFIRM', $monit);
		}
		$ft->parse('ROWS', ".result_note");
		break;

	default:
	
		// przydzielenie zmiennych::array
		$ft->assign(array(
						'SUBMIT_URL'		=>"main.php?p=$8&amp;action=add",
						'CATNAME_VALUE'		=>"",
						'CATNAME_DESC'		=>"",
						'SUBMIT_HREF_DESC'	=>"dodaj now� kategori�",
						'HEADER_DESC'		=>"<b>Kategorie - dodaj now� kategori�</b>"
		));
		
		// w przypadku braku akcji wy�wietlanie formularza
		$ft->define('form_category', "form_category.tpl");
		$ft->parse('ROWS', ".form_category");
}

?>
