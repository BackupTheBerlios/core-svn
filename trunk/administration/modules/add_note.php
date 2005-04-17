<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

// definicja szablonow parsujacych wyniki bledow.
$ft->define("error_reporting", "error_reporting.tpl");
$ft->define_dynamic("error_row", "error_reporting");

switch ($action) {
    
	case "add":
	
        if($permarr['writer']) {
	
            //sprawdzania daty
            $date = $_POST['date'] == 1 ? date("Y-m-d H:i:s") : $_POST['date'];
            
            $text = nl2br($_POST['text']);
		
            $title 			= $_POST['title'];
            $author 		= $_POST['author'];
            $category_id 	= $_POST['category_id'];
            $comments_allow = $_POST['comments_allow'];
            $published 		= $_POST['published'];
		
            $query = sprintf("
                INSERT INTO 
                    %1\$s 
                VALUES 
                    ('','%2\$d', '%3\$s','%4\$s','%5\$s','%6\$s', '', '%7\$d', '%8\$s')",
		
                $mysql_data['db_table'],
                $category_id,
                $date,
                addslashes($title),
                $author,
                addslashes($text),
                $comments_allow,
                $published
            );
            
            $db->query($query);
        
            $query = sprintf("
                SELECT 
                    max(id) as maxid 
                FROM 
                    %1\$s",
        
                $mysql_data['db_table']
            );
            
            $db->query($query);
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
			    
                    $query = sprintf("
                        UPDATE 
                            %1\$s 
                        SET 
                            image = '%2\$s' 
                        WHERE 
                            id = '%3\$d'", 
			    
                        $mysql_data['db_table'],
                        $file,
                        $id
                    );
                
				    $db->query($query);
				
				    $ft->assign('CONFIRM', $i18n['add_note'][0]);
				    $ft->parse('ROWS',	".result_note");
                }
            }
		
            $ft->assign('CONFIRM', $i18n['add_note'][1]);
            $ft->parse('ROWS',	".result_note");
        } else {
            
            $monit[] = $i18n['add_note'][2];

            foreach ($monit as $error) {
    
                $ft->assign('ERROR_MONIT', $error);
                    
                $ft->parse('ROWS',	".error_row");
            }
                        
            $ft->parse('ROWS', "error_reporting");
        }
		break;

	default:
	
		$query = sprintf("
            SELECT 
                category_id, category_name 
            FROM 
                %1\$s",
		
            $mysql_data['db_table_category']
        );
            
        $db->query($query);
        
		while($db->next_record()) {
			
			$c_id 	= $db->f("category_id");
			$c_name = $db->f("category_name");
		
			$ft->assign(array(
                'C_ID'		=>$c_id,
                'C_NAME'	=>$c_name
            ));
								
			$ft->define("form_noteadd", "form_noteadd.tpl");
			$ft->define_dynamic("category_row", "form_noteadd");

			$ft->parse('ROWS',	".category_row");		
		
		}

		$ft->assign(array(
            'SESSION_LOGIN' =>$_SESSION['login'],
            'DATE'			=>date('Y-m-d H:i:s')
        ));

		$ft->parse('ROWS', "form_noteadd");
}

?>