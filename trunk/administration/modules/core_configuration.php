<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

// inicjalizacja instancji klasy MySQL_DB
$db = new MySQL_DB;
	

if (empty($action)) {
	
	// set {MAINPOSTS_PER_PAGE} variable
	// liczba listowanych wpisów w na stronie g³ównej::db
	$db->query("	SELECT config_value 
					FROM $mysql_data[db_table_config]
					WHERE config_name = 'mainposts_per_page'");
	
	$db->next_record();
	$mainposts_per_page = $db->f("config_value");
		
	$ft->assign('MAINPOSTS_PER_PAGE', $mainposts_per_page);
	
	// set {EDITPOSTS_PER_PAGE} variable
	// liczba listowanych wpisów w czê¶ci administracyjnej::db
	$db->query("	SELECT config_value 
					FROM $mysql_data[db_table_config] 
					WHERE config_name = 'editposts_per_page'");
	
	$db->next_record();
	$editposts_per_page = $db->f("config_value");
	
	$ft->assign('EDITPOSTS_PER_PAGE', $editposts_per_page);
	
	// set {TITLE_PAGE} variable
	// tytu³ strony, wy¶wietlany w miejscu title::db
	$db->query("	SELECT config_value 
					FROM $mysql_data[db_table_config] 
					WHERE config_name = 'title_page'");
	
	$db->next_record();
	$title_page = $db->f("config_value");
	
	$ft->assign('TITLE_PAGE', $title_page);
		
	// w przypadku braku akcji wy¶wietlanie formularza
	$ft->parse('ROWS', ".form_configuration");
	
} elseif ($action == "add") {
	
	class configValidate {
		
		var $monit = ""; // zmienna przechowuj±ca komunikaty b³êdów
		
		// metoda sprawdzaj±ca czy podana warto¶æ jest liczb± ca³kowit±
		function checkNumeric($v) {
			
			if(!is_numeric($v)) {
				
				$this->monit .= "Warto¶æ okre¶laj±ca liczbê postów musi byæ liczb± ca³kowit±.<br />";
				return FALSE;
			}
		}
		
		// metoda sprawdzaj±ca warto¶æ zmiennej pod wzglêdem
		// warto¶ci minimalnej i maksymalnej
		function checkValue($v, $min, $max, $desc) {
		
			if(($v < $min) || ($v > $max)) {
				
				$this->monit .= "Warto¶æ okre¶laj±ca liczbê postów " . $desc . " w musi byæ miêdzy " . $min . ", a " . $max . ".<br />";
				return FALSE;
			}
		}
	}
	
	// egzemplarz klasy validuj±cej ustawienia core
	$conf = new configValidate();
	
	$conf->checkNumeric($_POST['mainposts_per_page']);
	$conf->checkValue($_POST['mainposts_per_page'], 3, 10, "na stronie g³ównej");
	$conf->checkValue($_POST['editposts_per_page'], 15, 25, "w czê¶ci administracyjnej");
	
	if(empty($conf->monit)) {
		
		// set {MAINPOSTS_PER_PAGE} variable
		// liczba listowanych wpisów w na stronie g³ównej::db
		$db->query("	UPDATE $mysql_data[db_table_config] 
						SET config_value = '$_POST[mainposts_per_page]' 
						WHERE config_name = 'mainposts_per_page'");
	
		// set {TITLE_PAGE} variable
		// liczba listowanych wpisów w na stronie g³ównej::db
		$db->query("	UPDATE $mysql_data[db_table_config] 
						SET config_value = '$_POST[title_page]' 
						WHERE config_name = 'title_page'");
	
		// set {EDITOSTS_PER_PAGE} variable
		// liczba listowanych wpisów w na stronie g³ównej::db
		$db->query("	UPDATE $mysql_data[db_table_config] 
						SET config_value = '$_POST[editposts_per_page]' 
						WHERE config_name = 'editposts_per_page'");
	
		$ft->assign('CONFIRM', "Dane zosta³y zmodyfikowane.");
	
		$ft->parse('ROWS', ".result_note");
	
	} else {
		
		$conf->monit .= "<br /><a href=\"javascript:history.back(-1);\">powrót</a>";
		
		$ft->assign('CONFIRM', $conf->monit);
		$ft->parse('ROWS', ".result_note");

	}
}
?>
