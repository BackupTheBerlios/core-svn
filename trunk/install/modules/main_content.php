<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {
	
	case "send":
	
		$monit	= ""; // zmienna przechowuj±ca b³êdy
	
		$dbname = $_POST['dbname'];
		$dbhost = $_POST['dbhost'];
		$dbuser = $_POST['dbuser'];
		$dbpass = $_POST['dbpass'];
		
		$dbprefix	= $_POST['dbprefix'];
		
		$coreuser	= $_POST['coreuser'];
		$coremail	= $_POST['coremail'];
		
		$corepass_1	= $_POST['corepass_1'];
		$corepass_2	= $_POST['corepass_2'];
		
		if($corepass_1 != $corepass_2) {
			
			$monit .= "Podane has³a nowego u¿ytkownika nie zgadzaj± siê ze sob±.<br />";
		}
		
		$db = new DB_Sql;
		$db->connect($dbname, $dbhost, $dbuser, $dbpass);
		
		$rdbms = empty($_POST['rdbms']) ? '' : $_POST['rdbms'];
		
		switch ($rdbms) {
			
			case 'mysql4':
				$db_schema = '../core-mysql40.sql';
				break;
				
			case 'mysql41':
				$db_schema = '../core-mysql41.sql';
				break;
		}	
					
		
		$sql_query = @fread(@fopen($db_schema, 'r'), @filesize($db_schema));
		$sql_query = preg_replace('/core_/', $dbprefix, $sql_query);
		
		$delimiter = ';';
		
		$sql = explode($delimiter, $sql_query);
		
		for ($i = 0; $i < sizeof($sql); $i++) {
			
			$db->query($sql[$i]);
		}
		
			
		$ft->assign('MONIT', $monit);
		$ft->define('monit_content', "monit_content.tpl");
		$ft->parse('ROWS', ".monit_content");
		break;

	default:
		
		$ft->assign(array(	'HOST'		=>'localhost',
							'PREFIX'	=>'core_',));
		$ft->define('main_content', "main_content.tpl");
		$ft->parse('ROWS', ".main_content");
		break;
}	

?>