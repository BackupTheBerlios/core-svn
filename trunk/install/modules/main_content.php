<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {
	
	case "send":
		
		define('SQL_SCHEMA', 'dbschema');
		
		$err	= ""; // zmienna przechowuj±ca b³edy
		$monit	= array(); // tablica przechowuj±ca b³êdy
		
		$monit['strlenuser']	= "Nazwa u¿ytkownika musi mieæ conajmniej 4 znaki.";
		$monit['strlenpass']	= "Has³o nowego u¿ytkownika musi mieæ conajmniej 6 znaków.";
		$monit['validemail']	= "Podaj poprawny adres e-mail.";
		$monit['diffpass']		= "Podane has³a nowego u¿ytkownika nie zgadzaj± siê ze sob±.";
		
		
		$warn	= "<span>Uwaga:</span><br />";
	
		$dbname = $_POST['dbname'];
		$dbhost = $_POST['dbhost'];
		$dbuser = $_POST['dbuser'];
		$dbpass = $_POST['dbpass'];
		
		$dbprefix	= $_POST['dbprefix'];
		
		$coreuser	= $_POST['coreuser'];
		$coremail	= $_POST['coremail'];
		
		$corepass_1	= $_POST['corepass_1'];
		$corepass_2	= $_POST['corepass_2'];
		
		if(strlen($coreuser) < 4) {
			
			$err .= $monit['strlenuser'] . "<br />";
		}
		
		if(!eregi("^([[:alnum:]]|_|\.|-)+@([[:alnum:]]|\.|-)+(\.)([a-z]{2,4})$", $coremail)) {
			
			$err .= $monit['validemail'] . "<br />";
		}
		
		if(strlen($corepass_1) < 6) {
				
			$err .= $monit['strlenpass'] . "<br />";
		}
		
		if($corepass_1 != $corepass_2) {
				
			$err .= $monit['diffpass'] . "<br />";
		}
		
		$rdbms = empty($_POST['rdbms']) ? '' : $_POST['rdbms'];
		
		switch ($rdbms) {
			
			case 'mysql4':
				$db_schema = SQL_SCHEMA . '/core-mysql40.sql';
				break;
				
			case 'mysql41':
				$db_schema = SQL_SCHEMA . '/core-mysql41.sql';
				break;
		}	
					
		if(empty($err)) {
			
			$db = new DB_Sql;
			$db->connect($dbname, $dbhost, $dbuser, $dbpass);
				
			
			$sql_query = @fread(@fopen($db_schema, 'r'), @filesize($db_schema));
			$sql_query = preg_replace('/core_/', $dbprefix, $sql_query);
		
			$delimiter = ';';
		
			$sql = explode($delimiter, $sql_query);
		
			for ($i = 0; $i < sizeof($sql); $i++) {
			
				$db->query($sql[$i]);
			}
				
			$file = '<?php'."\n\n";
			$file .= "\n// Core - plik konfiguracyjny wygenerowany automatycznie\n\n";
			$file .= "class MySQL_DB extends DB_Sql {\n\n";
			$file .= "\t" . 'var $Host = \'' . $dbhost . '\';' . "\n";
			$file .= "\t" . 'var $Database = \'' . $dbname . '\';' . "\n";
			$file .= "\t" . 'var $User = \'' . $dbuser . '\';' . "\n";
			$file .= "\t" . 'var $Password = \'' . $dbpass . '\';' . "\n";
			$file .= "}\n\n";
			$file .= 'define(\'PREFIX\', \'' . $dbprefix . '\');'."\n\n";
			
			$file .= '$mysql_data = array(' . "\n";
			$file .= "\t" . "'db_table'				=>PREFIX . 'devlog',\n";
			$file .= "\t" . "'db_table_users'		=>PREFIX . 'users',\n";
			$file .= "\t" . "'db_table_comments'	=>PREFIX . 'comments',\n";
			$file .= "\t" . "'db_table_config'		=>PREFIX . 'config',\n";
			$file .= "\t" . "'db_table_counter'		=>PREFIX . 'counter',\n";
			$file .= "\t" . "'db_table_category'	=>PREFIX . 'category',\n";
			$file .= "\t" . "'db_table_pages'		=>PREFIX . 'pages',\n";
			$file .= "\t" . "'db_table_links'		=>PREFIX . 'links',\n";
			$file .= ");\n\n";
			
			$file .= 'define(\'CORE_INSTALLED\', true);'."\n\n";
			
			$file .= '$days_to = 360;' . "\n\n";
			$file .= '?' . '>';
				
			$fp = @fopen('../administration/inc/config.php', 'w');
			$result = @fputs($fp, $file, strlen($file));
			@fclose($fp);
			
			$pass	= md5($corepass_1);
			$t1		= $dbprefix . 'users';
			$t2		= $dbprefix . 'category';
			$t3		= $dbprefix . 'counter';
			
			// wstawiamy pocz±tkowego u¿ytkownika
			$query = "	INSERT INTO 
							$t1 
						VALUES
							('', '$coreuser', '$pass', '$coremail', 'Y')";
			
			$db->query($query);
			
			// wstawiamy domy¶lnie kategoriê ogóln±
			$query = "	INSERT INTO 
							$t2 
						VALUES
							('', 'ogólna', '')";
			
			$db->query($query);
			
			// ustawiamy warto¶æ licznika na 0
			$query = "	INSERT INTO 
							$t3 
						VALUES
							('', '', '0')";
			
			$db->query($query);
			
			$err .= "Instalacja przebig³a pomy¶lnie.";
			
			$ft->assign('MONIT', $err);
			$ft->define('monit_content', "monit_content.tpl");
			
			$ft->parse('ROWS', ".monit_content");
		} else {
			
			$ft->assign(array(	'MONIT'	=>$err,
								'WARN'	=>$warn));
								
			$ft->define('monit_content', "monit_content.tpl");
			$ft->define('back_content', "back_content.tpl");
			
			$ft->parse('ROWS', ".monit_content");
			$ft->parse('ROWS', ".back_content");
		}
		break;

	default:
		
		$ft->assign(array(	'HOST'		=>'localhost',
							'PREFIX'	=>'core_',));
		$ft->define('main_content', "main_content.tpl");
		$ft->parse('ROWS', ".main_content");
		break;
}	

?>