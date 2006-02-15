<?php
// $Id$

/*
 * This file is internal part of Core CMS (http://core-cms.com/) engine.
 *
 * Copyright (C) 2004-2005 Core Dev Team (more info: docs/AUTHORS).
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published
 * by the Free Software Foundation; version 2 only.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 */

$m      = empty($_GET['m']) ? '' : $_GET['m'];
$token  = empty($_GET['token']) ? '' : $_GET['token'];
$email  = isset($_POST['email']) ? $_POST['email'] : $_POST['email'];

switch($m){
    
    case 'sign_in':
    
        if(!empty($token)) {
    
            $query = sprintf("
                UPDATE 
                    %1\$s 
                SET 
                    active = active * -1 
                WHERE 
                    token = '%2\$s'", 
    
                TABLE_NEWSLETTER, 
                $token
            );
            
            $db->query($query);
            
            $ft->assign('CONFIRM', $i18n['newsletter'][8]);
        } else {
    
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
                    VALUES
                        ('', '%2\$s', '-1', '%3\$s')", 
            
                    TABLE_NEWSLETTER, 
                    $email, 
                    md5($email)
                );
            
                $db->query($query);
            
                if($db->next_record() == 0) {
                
                    $ft->assign(array(
                        'CONFIRM'	=>$i18n['newsletter'][1],
                        'STRING'	=>""
                    ));
                
                    $i18n['newsletter'][6] = str_replace('[link_1]', $_SERVER['HTTP_HOST'], $i18n['newsletter'][6]);
                    $i18n['newsletter'][6] = str_replace(
                        '[link_2]', 
                        $_SERVER['HTTP_HOST'] . '/?p=newsletter&m=sign_in&token=' . md5($email), 
                        $i18n['newsletter'][6]
                    );
                    $i18n['newsletter'][7] = str_replace('[link_1]', $_SERVER['HTTP_HOST'], $i18n['newsletter'][7]);
                
                    mail($email, $i18n['newsletter'][7], $i18n['newsletter'][6]);
                } else {
                
                    $ft->assign(array(
                        'CONFIRM'	=>$i18n['newsletter'][2],
                        'STRING'	=>""
                    ));
                }
            }
        }
        
        $ft->parse('MAIN', ".newsletter");
        break;
    
    case 'sign_out':
    
        if(!empty($token)) {
    
            $query = sprintf("
                DELETE FROM 
                    %1\$s 
                WHERE 
                    token = '%2\$s'", 
    
                TABLE_NEWSLETTER, 
                $token
            );
            
            $db->query($query);
            
            $ft->assign('CONFIRM', $i18n['newsletter'][4]);
        } else {
    
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
            
                $ft->assign(array(
                    'CONFIRM'   =>$i18n['newsletter'][10],
                    'STRING'    =>""
				));
                
				$i18n['newsletter'][9] = str_replace('[link_1]', $_SERVER['HTTP_HOST'], $i18n['newsletter'][9]);
                $i18n['newsletter'][9] = str_replace(
                    '[link_3]', 
                    $_SERVER['HTTP_HOST'] . '/?p=newsletter&m=sign_out&token=' . md5($email), 
                    $i18n['newsletter'][9]
                );
                $i18n['newsletter'][7] = str_replace('[link_1]', $_SERVER['HTTP_HOST'], $i18n['newsletter'][7]);
                
                mail($email, $i18n['newsletter'][7], $i18n['newsletter'][9]);
                
            }
        }
        
        $ft->parse('MAIN', ".newsletter");
        break;
}

$ft->assign(array(
    'STRING'        =>'', 
    'PAGINATED'     =>false, 
    'MOVE_BACK'     =>false, 
    'MOVE_FORWARD'  =>false
));

?>
