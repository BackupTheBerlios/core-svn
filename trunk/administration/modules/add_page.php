<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch($action) {
	
	case "add":
		$text = nl2br($_POST['text']);
		
		$title 		= $_POST['title'];
		$published 	= $_POST['published'];
		$page_id	= $_POST['category_id'];
		
		$query = sprintf("
            INSERT INTO 
                %1\$s 
            VALUES 
                ('', '%2\$d', '%3\$s', '%4\$s', '', '%5\$s')", 
		
            $mysql_data['db_table_pages'], 
            $page_id, 
            $title, 
            $text, 
            $published
        );
		
		$db->query($query);
		
		$query = sprintf("
            SELECT MAX(id) 
                as maxid 
            FROM 
                %1\$s", 

            $mysql_data['db_table_pages']
        );
		
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
			
				$query = sprintf("
                    UPDATE 
                        %1\$s 
                    SET 
                        image = '%2\$s' 
                    WHERE 
                        id = '%3\$d'", 
			    
                    $mysql_data['db_table_pages'],
                    $file,
                    $id
                );
				
				$db->query($query);
				
				$ft->assign('CONFIRM', "Zdjêcie zosta³o dodane.<br />");
				$ft->parse('ROWS',	".result_note");
			}
		}
		
		$ft->assign('CONFIRM', "Strona zosta³a dodana");
		$ft->parse('ROWS',	".result_note");
		break;

	default:
		
	$query = sprintf("
        SELECT 
            id, parent_id, title 
        FROM 
            %1\$s 
        WHERE 
            published = 'Y' 
        AND 
            parent_id = '%2\$d' 
        ORDER BY 
            id 
        ASC", 
	
	   $mysql_data['db_table_pages'],
	   0);
	
	$db->query($query);
	while($db->next_record()) {
		
		$page_id      = $db->f("id");
		$parent_id    = $db->f("parent_id");
		$title        = $db->f("title");
	
		$ft->assign(array(
            'C_ID'		=>$page_id,
            'C_NAME'	=>$title
        ));
							
		$ft->define("form_pageadd", "form_pageadd.tpl");
        $ft->define_dynamic("page_row", "form_pageadd");
        
        $ft->parse('ROWS',	".page_row");
        
		get_addpage_cat($page_id, 2);				
	
	}

	$ft->parse('ROWS',	"form_pageadd");
}

?>