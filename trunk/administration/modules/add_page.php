<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

$db = new DB_SQL;

switch($action) {
	
	case "add":
		$text = nl2br($_POST['text']);
		
		$title 		= $_POST['title'];
		$published 	= $_POST['published'];
		$page_id	= $_POST['category_id'];
		
		$query = "	INSERT INTO 
						$mysql_data[db_table_pages] 
					VALUES 
						('', '$page_id', '$title', '$text', '', '$published')";
		
		$db->query($query);
		
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
				
				$ft->assign('CONFIRM', "Zdjêcie zosta³o dodane.<br />");
				$ft->parse('ROWS',	".result_note");
			}
		}
		
		$ft->assign('CONFIRM', "Strona zosta³a dodana");
		$ft->parse('ROWS',	".result_note");
		break;

	default:	
	$query	= "	SELECT 
					id, parent_id, title 
				FROM 
					$mysql_data[db_table_pages] 
				WHERE 
					published = 'Y' 
				AND 
					parent_id = '0'
				ORDER BY 
					id 
				ASC";
	
	$db->query($query);
	while($db->next_record()) {
		
		$page_id 		= $db->f("id");
		$parent_id 		= $db->f("parent_id");
		$title 			= $db->f("title");
	
		$ft->assign(array(	'C_ID'		=>$page_id,
							'C_NAME'	=>$title));
							
		$ft->define('page_categoryoption', "page_categoryoption.tpl");				
		$ft->parse('CATEGORY_ROWS', ".page_categoryoption");
		get_addpage_cat($page_id, 2);				
	
	}

	$ft->define('form_pageadd', "form_pageadd.tpl");
	$ft->parse('ROWS',	".form_pageadd");
}

?>
