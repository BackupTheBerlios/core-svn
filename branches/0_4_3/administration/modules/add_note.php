<?php

// deklaracja zmiennej $action::form
$post       = empty($_POST['post']) ? '' : $_POST['post'];
$preview    = empty($_POST['preview']) ? '' : $_POST['preview'];

// definicja szablonow parsujacych wyniki bledow.
$ft->define("error_reporting", "error_reporting.tpl");
$ft->define_dynamic("error_row", "error_reporting");

if(!empty($post)) {
	
        if($permarr['writer']) {
            if(isset($_POST['assign2cat'])) {
                
                //sprawdzania daty
                $date = isset($_POST['now']) ? date("Y-m-d H:i:s") : $_POST['date'];
                
                $text           = $_POST['text'];
                $title 			= $_POST['title'];
                $author 		= $_POST['author'];
                $comments_allow = $_POST['comments_allow'];
                $published 		= $_POST['published'];
                $only_in_cat    = $_POST['only_in_category'];
                $assign2cat     = $_POST['assign2cat'];
                
                $text = parse_markers($text, 1);
                
                $query = sprintf("
                    INSERT INTO 
                        %1\$s 
                    VALUES 
                        ('', '%2\$s','%3\$s','%4\$s','%5\$s', '', '%6\$d', '%7\$s', '%8\$s')",
		
                    TABLE_MAIN,
                    $date,
                    $title,
                    $author,
                    $text,
                    $comments_allow,
                    $published, 
                    $only_in_cat
                );
            
                $db->query($query);
        
                $query = sprintf("
                    SELECT 
                        max(id) as maxid 
                    FROM 
                        %1\$s",
        
                    TABLE_MAIN
                );
            
                $db->query($query);
                $db->next_record();
			
                // Przypisanie zmiennej $id
                $id = $db->f("0");
            
                foreach ($assign2cat as $selected_cat) {
                    $query = sprintf("
                        INSERT INTO 
                            %1\$s 
                        VALUES('', '%2\$d', '%3\$d')", 
                
                        TABLE_ASSIGN2CAT, 
                        $id, 
                        $selected_cat
                    );
                    $db->query($query);
                }
		
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
			    
                            TABLE_MAIN,
                            $file,
                            $id
                        );
                
				        $db->query($query);
				
				        $ft->assign('CONFIRM', $i18n['add_note'][0]);
				        $ft->parse('ROWS', ".result_note");
                    }
                }
		
                $ft->assign('CONFIRM', $i18n['add_note'][1]);
                $ft->parse('ROWS',	".result_note");
            } else {
                
                $monit[] = $i18n['add_note'][3];
                foreach ($monit as $error) {
    
                    $ft->assign('ERROR_MONIT', $error);
                    
                    $ft->parse('ROWS',	".error_row");
                }
                        
                $ft->parse('ROWS', "error_reporting");
            }
        } else {
            
            $monit[] = $i18n['add_note'][2];

            foreach ($monit as $error) {
    
                $ft->assign('ERROR_MONIT', $error);
                    
                $ft->parse('ROWS',	".error_row");
            }
                        
            $ft->parse('ROWS', "error_reporting");
        }

} else {
    
    if(!empty($preview)) {
        $text   = $_POST['text'];
        $title  = trim($_POST['title']);
        
        $ft->assign(array(
            'N_TITLE'       =>stripslashes($title), 
            'N_TEXT'        =>br2nl(stripslashes($text)), 
            'NT_TEXT'       =>nl2br(parse_markers(stripslashes($text), 1)), 
            'NOTE_PREVIEW'  =>true
        ));
    } else {
        $ft->assign('NOTE_PREVIEW', false);
    }
	
    $query = sprintf("
        SELECT 
            category_id, 
            category_parent_id,
            category_name 
        FROM 
            %1\$s 
        WHERE 
            category_parent_id = '%2\$d' 
        ORDER BY 
            category_id 
        ASC", 
	
        TABLE_CATEGORY,
        0
    );
	
    $db->query($query);
	
    $ft->define("form_noteadd", "form_noteadd.tpl");
    $ft->define_dynamic("cat_row", "form_noteadd");
        
    while($db->next_record()) {
		
        $cat_id         = $db->f("category_id");
        $cat_parent_id  = $db->f("category_parent_id");
        $cat_name       = $db->f("category_name");
            
        $ft->assign(array(
            'C_ID'		    =>$cat_id,
            'C_NAME'        =>$cat_name, 
            'CURRENT_CAT'   =>$cat_id == 1 ? 'checked="checked"' : '', 
            'PAD'           =>''
        ));
        
        $ft->parse('CAT_ROW', ".cat_row");
        
        get_addcategory_assignedcat($cat_id, 2);
    }

    $ft->assign(array(
        'SESSION_LOGIN' =>$_SESSION['login'],
        'DATE'			=>date('Y-m-d H:i:s')
    ));

    $ft->parse('ROWS', "form_noteadd");
}

?>