<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

// g³ówny switcher::action
if($action == "add") {
	
	$text = nl2br($_POST['text']);
	
	$title 			= $_POST['title'];
	$published 		= $_POST['published'];
	
	$data_base = new MySQL_DB;
	$data_base->query("INSERT INTO $mysql_data[db_table_pages] VALUES ('', '$title', '$text', '', '$published')");
	$data_base->next_record();
	
	$d_b = new MySQL_DB;
 	$d_b->query("SELECT max(id) as maxid FROM $mysql_data[db_table_pages]");
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
 			$d_base->query("UPDATE $mysql_data[db_table_pages] 
 							SET image='$file' 
 							WHERE (id='$id')");
			$d_base->next_record();
			
			$ft->assign('CONFIRM', "Zdjêcie zosta³o dodane.<br />");
			$ft->parse('ROWS',	".result_note");
		}
	}
	
	$ft->assign('CONFIRM', "Wpis zosta³ dodany");
	$ft->parse('ROWS',	".result_note");
	
} else {

	$ft->parse('ROWS',	".form_pageadd");
}

?>