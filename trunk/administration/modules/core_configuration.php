<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

// inicjalizacja instancji klasy MySQL_DB
$db = new MySQL_DB;
	

if (empty($action)) {
	
	// set {MAINPOSTS_PER_PAGE} variable
	// liczba listowanych wpis�w w na stronie g��wnej::db
	$db->query("	SELECT config_value 
					FROM $mysql_data[db_table_config]
					WHERE config_name = 'mainposts_per_page'");
	
	$db->next_record();
	$mainposts_per_page = $db->f("config_value");
		
	$ft->assign('MAINPOSTS_PER_PAGE', $mainposts_per_page);
	
	// set {EDITPOSTS_PER_PAGE} variable
	// liczba listowanych wpis�w w cz�ci administracyjnej::db
	$db->query("	SELECT config_value 
					FROM $mysql_data[db_table_config] 
					WHERE config_name = 'editposts_per_page'");
	
	$db->next_record();
	$editposts_per_page = $db->f("config_value");
	
	$ft->assign('EDITPOSTS_PER_PAGE', $editposts_per_page);
	
	// set {TITLE_PAGE} variable
	// tytu� strony, wy�wietlany w miejscu title::db
	$db->query("	SELECT config_value 
					FROM $mysql_data[db_table_config] 
					WHERE config_name = 'title_page'");
	
	$db->next_record();
	$title_page = $db->f("config_value");
	
	$ft->assign('TITLE_PAGE', $title_page);
		
	// w przypadku braku akcji wy�wietlanie formularza
	$ft->parse('ROWS', ".form_configuration");
	
} elseif ($action == "add") {
	
	class configValidate {
		
		var $monit = ""; // zmienna przechowuj�ca komunikaty b��d�w
		
		// metoda sprawdzaj�ca czy podana warto�� jest liczb� ca�kowit�
		function checkNumeric($v) {
			
			if(!is_numeric($v)) {
				
				$this->monit .= "Warto�� okre�laj�ca liczb� post�w musi by� liczb� ca�kowit�.<br />";
				return FALSE;
			}
		}
		
		// metoda sprawdzaj�ca warto�� zmiennej pod wzgl�dem
		// warto�ci minimalnej i maksymalnej
		function checkValue($v, $min, $max, $desc) {
		
			if(($v < $min) || ($v > $max)) {
				
				$this->monit .= "Warto�� okre�laj�ca liczb� post�w " . $desc . " w musi by� mi�dzy " . $min . ", a " . $max . ".<br />";
				return FALSE;
			}
		}
	}
	
	// egzemplarz klasy validuj�cej ustawienia core
	$conf = new configValidate();
	
	$conf->checkNumeric($_POST['mainposts_per_page']);
	$conf->checkValue($_POST['mainposts_per_page'], 3, 10, "na stronie g��wnej");
	$conf->checkValue($_POST['editposts_per_page'], 15, 25, "w cz�ci administracyjnej");
	
	if(empty($conf->monit)) {
		
		// set {MAINPOSTS_PER_PAGE} variable
		// liczba listowanych wpis�w w na stronie g��wnej::db
		$db->query("	UPDATE $mysql_data[db_table_config] 
						SET config_value = '$_POST[mainposts_per_page]' 
						WHERE config_name = 'mainposts_per_page'");
	
		// set {TITLE_PAGE} variable
		// liczba listowanych wpis�w w na stronie g��wnej::db
		$db->query("	UPDATE $mysql_data[db_table_config] 
						SET config_value = '$_POST[title_page]' 
						WHERE config_name = 'title_page'");
	
		// set {EDITOSTS_PER_PAGE} variable
		// liczba listowanych wpis�w w na stronie g��wnej::db
		$db->query("	UPDATE $mysql_data[db_table_config] 
						SET config_value = '$_POST[editposts_per_page]' 
						WHERE config_name = 'editposts_per_page'");
	
		$ft->assign('CONFIRM', "Dane zosta�y zmodyfikowane.");
	
		$ft->parse('ROWS', ".result_note");
	
	} else {
		
		$conf->monit .= "<br /><a href=\"javascript:history.back(-1);\">powr�t</a>";
		
		$ft->assign('CONFIRM', $conf->monit);
		$ft->parse('ROWS', ".result_note");

	}
}
?>
