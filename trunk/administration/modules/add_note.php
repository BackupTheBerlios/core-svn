<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

// inicjalizacja instancji klasy DB_SQL
$db = new DB_SQL;

switch ($action)
{
	case "add":
		//sprawdzania daty
		if($_POST['date'] == 1) {
			
			$date = date("Y-m-d H:i:s");
		} else {
			
			$date = $_POST['date'];
		}	
		
		$text = nl2br($_POST['text']);
		
		$title 			= $_POST['title'];
		$author 		= $_POST['author'];
		$category_id 	= $_POST['category_id'];
		$comments_allow = $_POST['comments_allow'];
		$published 		= $_POST['published'];
		
		$db->query("INSERT INTO $mysql_data[db_table] VALUES ('','$category_id', '$date','$title','$author','$text', '', '$comments_allow', '$published')");
		
 		$db->query("SELECT max(id) as maxid FROM $mysql_data[db_table]");
 		$db->next_record();
			
		// Przypisanie zmiennej $id
		$id = $db->f("0");
		
		if(!empty($_FILES['file']['name'])) {
			
			$up = new upload;
			$upload_dir = "../photos";
			
			// use function to upload file.
			$file = $up->upload_file($upload_dir, 'file', true, true, 0, "jpg|jpeg|gif");
			if($file == false) {
				
				echo $up->error;
			} else {
			
 				$db->query("UPDATE $mysql_data[db_table] 
 								SET image='$file' 
 								WHERE (id='$id')");
				$db->next_record();
				
				$ft->assign('CONFIRM', "Zdjêcie zosta³o dodane.<br />");
				$ft->parse('ROWS',	".result_note");
			}
		}
		
		$ft->assign('CONFIRM', "Wpis zosta³ dodany");
		$ft->parse('ROWS',	".result_note");
		
		//print "<p align=\"center\"><a href=\"index2.php?p=mail\">Wyœlij potwierdzenie</a>";
		break;

	default:
		$db->query("SELECT category_id, category_name FROM $mysql_data[db_table_category]");
		while($db->next_record()) {
			
			$c_id 	= $db->f("category_id");
			$c_name = $db->f("category_name");
		
			$ft->assign(array(	'C_ID'		=>$c_id,
								'C_NAME'	=>$c_name));
							
			$ft->define('form_categoryoption', "form_categoryoption.tpl");
			$ft->parse('CATEGORY_ROWS', ".form_categoryoption");					
		
		}

		$ft->assign(array(	'SESSION_LOGIN' =>$_SESSION['login'],
							'DATE'			=>date('Y-m-d H:i:s')));

		$ft->define('form_noteadd', "form_noteadd.tpl");
		$ft->parse('ROWS',	".form_noteadd");

}

?>
