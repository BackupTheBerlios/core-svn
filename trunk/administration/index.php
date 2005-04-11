<?php
session_register("login");
session_register("loggedIn");

setlocale(LC_CTYPE, "pl_PL");

if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === TRUE){
	
	header("Location: main.php");
	break;
}

define('PATH_TO_CLASSES', 'classes');

require(PATH_TO_CLASSES. '/cls_db_mysql.php');
require(PATH_TO_CLASSES. '/cls_phpmailer.php');

require("inc/config.php");

require(PATH_TO_CLASSES. '/cls_fast_template.php');

// warto¶æ pocz±tkowa zmiennej $start -> potrzebna przy stronnicowaniu
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;

// inicjowanie klasy, wkazanie katalogu przechowuj±cego szablony
$ft = new FastTemplate("./templates/tpl");

$ft->define(array(
    'main'              =>"main.tpl",
    'main_loader'       =>"main_loader.tpl",
    'rows'              =>"rows.tpl",
    'form_login'        =>"form_login.tpl"
));
		
$ft->assign(array(
    'TITLE'         =>"CORE - panel administracyjny",
    'ERROR_MSG'     =>""
));

// deklaracja zmiennej $p
$p = empty($_GET['p']) ? '' : $_GET['p'];

if ($p == "log") {
	
	$login		= $_POST['login'];
	$password	= md5($_POST['password']);
	
	if(empty($login) OR empty($password)) {
		
		// U¿ytkownik nie uzupe³ni³ wszystkich pól::form
		$ft->assign('ERROR_MSG', "Nie uzupe³niono wszystkich pól");
		$ft->parse('ROWS', ".form_login");
	} else {
		
		$db = new DB_SQL;
		$query = "	SELECT 
						login, password, active 
					FROM 
						$mysql_data[db_table_users] 
					WHERE 
						login = '$login' 
					AND 
						password = '$password'";
		
		$db->query($query);
	
		$user 	= $db->f("login");
		
		if($db->num_rows() > 0) {

			if(($active = $db->f("active")) !== "N") {
				
				// Rejestrujemy zmienne sesyjne
				$_SESSION["login"]		= $login;
				$_SESSION["loggedIn"]	= TRUE;
		
				if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === TRUE){
				
					header("Location: main.php");
					break;
				}
			} else {
				
				// U¿ytkownik nie zaaktywowa³ konta::db
				$ft->assign('ERROR_MSG', "Konto nie zosta³o jeszcze aktywowane");
				$ft->parse('ROWS', ".form_login");
			}
		} else {
			// Niepoprawne dane wej¶cia<->wyj¶cia::form, db
			$ft->assign('ERROR_MSG', "B³êdna nazwa u¿ytkownika, lub b³êdne has³o");
			$ft->parse('ROWS', ".form_login");
		}
	}
} else {
		
	include("modules/login.php");
	
}

$ft->parse('MAIN', array("main_loader", "main"));
$ft->FastPrint();
exit;

?>