<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

// g³ówny switcher::action
if($action == "add") {
	
	$text = nl2br($_POST['text']);
	
	$title 		= $_POST['title'];
	$published 	= $_POST['published'];
	
	$db = new MySQL_DB;
	
	$query = "	INSERT INTO 
					$mysql_data[db_table_pages] 
				VALUES 
					('', '$title', '$text', '', '$published')";
	
	$db->query($query);
	$db->next_record();
	
	
	$query = "	SELECT MAX(id) 
					as maxid 
				FROM 
					$mysql_data[db_table_pages]";
	
 	$db->query($query);
 	$db->next_record();
		
	// Przypisanie zmiennej $id
	$id = $db->f("0");
	
	if(!empty($_FILES['file']['name'])) {
		
		$up = new upload;
		$upload_dir = "../photos";
		
		// upload file.
		$file = $up->upload_file($upload_dir, 'file', true, true, 0, "jpg|jpeg|gif");
		if($file == false) {
			
			echo $up->error;
		} else {
		
			$query = "	UPDATE 
							$mysql_data[db_table_pages] 
						SET 
							image = '$file' 
						WHERE 
							id = '$id'";
			
			$db->query($query);
			$db->next_record();
			
			$ft->assign('CONFIRM', "Zdjêcie zosta³o dodane.<br />");
			$ft->parse('ROWS',	".result_note");
		}
	}
	
	$ft->assign('CONFIRM', "Strona zosta³a dodana");
	$ft->parse('ROWS',	".result_note");
	
} else {

	$ft->parse('ROWS',	".form_pageadd");
}

?>