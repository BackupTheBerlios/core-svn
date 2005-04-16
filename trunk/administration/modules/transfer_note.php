<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {
    
	case "add":
	
        $current    = $_POST['current_cat_id'];
        $target     = $_POST['target_cat_id'];
        
        $monit      = array();
        
        if(!is_numeric($current)) {
            
            $monit[] = $i18n['transfer_note'][0];
        }
        
        if(!is_numeric($target)) {
            
            $monit[] = $i18n['transfer_note'][1];
        }
        
        if(empty($monit)) {
            
            $query = sprintf("
                UPDATE 
                    %1\$s
                SET 
                    c_id = '%2\$d' 
                WHERE
                    c_id = '%3\$d'",
                
                $mysql_data['db_table'],
                $target, 
                $current
            );
            
            $db->query($query);
            
            $ft->assign('CONFIRM', $i18n['transfer_note'][2]);
            $ft->parse('ROWS',	".result_note");
        } else {
            
            $ft->define("error_reporting", "error_reporting.tpl");
            $ft->define_dynamic("error_row", "error_reporting");

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
                'CURRENT_CID'   =>$c_id,
                'TARGET_CID'    =>$c_id,
                'CURRENT_CNAME' =>$c_name,
                'TARGET_CNAME'  =>$c_name
            ));
								
			$ft->define(array(
                "form_notetransfer"     =>"form_notetransfer.tpl",
                "form_targetcategory"   =>"form_targetcategory.tpl"
            ));

			$ft->define_dynamic("current_row", "form_notetransfer");

            $ft->parse('ROWS',              ".current_row");
            $ft->parse('TARGET_CATEGORY',   ".form_targetcategory");	
		
		}

		$ft->assign(array(
            'SESSION_LOGIN' =>$_SESSION['login'],
            'DATE'			=>date('Y-m-d H:i:s')
        ));

		$ft->parse('ROWS', "form_notetransfer");
}

?>