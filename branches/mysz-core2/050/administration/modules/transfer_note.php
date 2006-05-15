<?php
// $Id: transfer_note.php 1213 2005-11-05 13:03:06Z mysz $

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

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {
    
	case "add":
	
        $current    = $_POST['current_cat_id'];
        $target     = $_POST['target_cat_id'];
        $monit      = array();
        
        // definicja szablonow parsujacych wyniki bledow.
        $ft->define("error_reporting", "error_reporting.tpl");
        $ft->define_dynamic("error_row", "error_reporting");
        
        if($permarr['moderator']) {
        
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
                        category_id = '%2\$d' 
                    WHERE
                        category_id = '%3\$d'",
                
                    TABLE_ASSIGN2CAT,
                    $target, 
                    $current
                );
            
                $db->query($query);
            
                $ft->assign('CONFIRM', $i18n['transfer_note'][2]);
                $ft->parse('ROWS',	".result_note");
            } else {

                foreach ($monit as $error) {
    
                    $ft->assign('ERROR_MONIT', $error);
                    
                    $ft->parse('ROWS',	".error_row");
                }
                        
                $ft->parse('ROWS', "error_reporting");
            }
        } else {
            
            $monit[] = $i18n['transfer_note'][3];
            
            foreach ($monit as $error) {
                
                $ft->assign('ERROR_MONIT', $error);
                
                $ft->parse('ROWS',	".error_row");
            }
                        
            $ft->parse('ROWS', "error_reporting");
        }
        break;

	default:
	
        $ft->define("form_notetransfer", "form_notetransfer.tpl");
        
        $ft->define_dynamic("current_row", "form_notetransfer");
        $ft->define_dynamic("target_row", "form_notetransfer");
	
		$query = sprintf("
            SELECT 
                category_id, 
                category_parent_id, 
                category_name 
            FROM 
                %1\$s 
            WHERE 
                category_parent_id = '%2\$d'",
		
            TABLE_CATEGORY, 
            0
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

            $ft->parse('CURRENT_ROW', ".current_row");
            $ft->parse('TARGET_ROW', ".target_row");
            
            // rekurencyjnie pobieramy kategorie wpisów
            get_transfercategory_cat($c_id, 2);	
		}

		$ft->assign(array(
            'SESSION_LOGIN' =>$_SESSION['login'],
            'DATE'			=>date('Y-m-d H:i:s')
        ));

		$ft->parse('ROWS', "form_notetransfer");
}

?>
