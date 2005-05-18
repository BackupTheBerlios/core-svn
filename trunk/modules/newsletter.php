<?php

$m      = empty($_GET['m']) ? '' : $_GET['m'];
$email  = isset($_POST['email']) ? $_POST['email'] : $_POST['email'];

switch($m){
    
    case 'sign_in':
    
        $query	= sprintf("
            SELECT * FROM 
                %1\$s 
            WHERE 
                email = '%2\$s'", 
        
            TABLE_NEWSLETTER,
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
                VALUES('$email')", 
            
                TABLE_NEWSLETTER
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
        
        $ft->parse('MAIN', ".newsletter");
        break;
    
    case 'sign_out':
    
        $query	= sprintf("
            SELECT * FROM 
                %1\$s 
            WHERE 
                email = '%2\$s'", 
        
            TABLE_NEWSLETTER, 
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
                    email = '$email'", 
            
                TABLE_NEWSLETTER
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
        $ft->parse('MAIN', ".newsletter");
        break;
}

?>