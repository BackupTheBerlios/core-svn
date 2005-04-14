<?php

$m      = empty($_GET['m']) ? '' : $_GET['m'];
$email  = isset($_POST['email']) ? intval($_POST['email']) : '';

switch($m){
    
    case 'sign_in':
    
        $query	= sprintf("
            SELECT * FROM 
                %1\$s 
            WHERE 
                email = '%2\$s'", 
        
            $mysql_data['db_table_newsletter'],
            $email
        );
        
        $db->query($query);
        
        if($db->next_record() > 0) {
            
            $ft->assign(array(
                'CONFIRM'	=>$i18n['newsletter'][0],
                'STRING'	=>""
            ));
        } else {
            
            $query = sprintf("
                INSERT INTO 
                    %1\$s 
                VALUES('%2\$s')", 
            
                $mysql_data['db_table_newsletter'],
                $email
            );
            
            $db->query($query);
            
            if($db->next_record() == 0) {
                
                $ft->assign(array(
                    'CONFIRM'	=>$i18n['newsletter'][1],
					'STRING'	=>""
                ));
            } else {
                
                $ft->assign(array(
                    'CONFIRM'	=>$i18n['newsletter'][2],
                    'STRING'	=>""
                ));
            }
        }
        
        $ft->parse('ROWS', ".newsletter");
        break;
    
    case 'sign_out':
    
        $query	= sprintf("
            SELECT * FROM 
                %1\$s 
            WHERE 
                email = '%2\$s'", 
        
            $mysql_data['db_table_newsletter'], 
            $email
        );
        
        $db->query($query);
        
        if($db->next_record() == 0) {
            
            $ft->assign(array(
                'CONFIRM'   =>$i18n['newsletter'][3],
				'STRING'    =>""
            ));
        } else {
            
            $query	= sprintf("
                DELETE FROM 
                    %1\$s 
                WHERE 
                    email = '%2\$s'", 
            
                $mysql_data['db_table_newsletter'], 
                $email
            );
            
            $db->query($query);
            
            if($db->next_record() == 0) {
                
                $ft->assign(array(
                    'CONFIRM'   =>$i18n['newsletter'][4],
					'STRING'   =>""
				));
            } else {
                
                $ft->assign(array(
                    'CONFIRM'   =>$i18n['newsletter'][5],
                    'STRING'    =>""
                ));
            }
        }
        $ft->parse('ROWS', ".newsletter");
        break;
}

?>