<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

// g³ówny switcher::action
if($action == "add") {
	
	$tmp = time();
	
	//sprawdzania daty
	if($_POST['date'] == 1) {
		
		$date = date("Y-m-d H:i:s", $tmp);
	} else {
		
		$date = $_POST['date'];
	}	
	
	$text = nl2br($_POST['text']);
	
	$title 			= $_POST['title'];
	$author 		= $_POST['author'];
	$category_id 	= $_POST['category_id'];
	$comments_allow = $_POST['comments_allow'];
	$published 		= $_POST['published'];
	
	$data_base = new MySQL_DB;
	$data_base->query("INSERT INTO $mysql_data[db_table] VALUES ('','$category_id', '$date','$title','$author','$text', '', '$comments_allow', '$published')");
	$data_base->next_record();
	
	$d_b = new MySQL_DB;
 	$d_b->query("SELECT max(id) as maxid FROM $mysql_data[db_table]");
 	$d_b->next_record();
		
	// Przypisanie zmiennej $id
	$id = $d_b->f("0");
	
	if($_FILES['file'] !== '') {
		
		$up = new upload;
		$upload_dir = "../photos";
		
		// use function to upload file.
		$file = $up->upload_file($upload_dir, 'file', true, true, 0, "jpg|jpeg|gif");
		if($file == false) {
			
			echo $up->error;
		} else {
		
 			$d_base = new MySQL_DB;
 			$d_base->query("UPDATE $mysql_data[db_table] 
 							SET image='$file' 
 							WHERE (id='$id')");
			$d_base->next_record();
			
			$ft->assign('CONFIRM', "Zdjêcie zosta³o dodane.<br />");
			$ft->parse('ROWS',	".result_note");
		}
	}
	
	$ft->assign('CONFIRM', "Wpis zosta³ dodany");
	$ft->parse('ROWS',	".result_note");
	
	//print "<p align=\"center\"><a href=\"index2.php?p=mail\">Wyœlij potwierdzenie</a>";
} else {
	
	$dbase = new MySQL_DB;
	$dbase->query("SELECT category_id, category_name FROM $mysql_data[db_table_category]");
	while($dbase->next_record()) {
		
		$c_id 	= $dbase->f("category_id");
		$c_name = $dbase->f("category_name");
	
		$ft->assign(array(	'C_ID'		=>$c_id,
							'C_NAME'	=>$c_name));
						
		$ft->parse('CATEGORY_ROWS', ".form_categoryoption");					
	
	}

	$ft->assign(array(	'SESSION_LOGIN' =>$_SESSION['login'],
						'DATE'			=>date('Y-m-d H:i:s')));

	$ft->parse('ROWS',	".form_noteadd");
}

?>