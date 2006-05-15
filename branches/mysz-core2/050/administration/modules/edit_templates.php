<?php
// $Id: edit_templates.php 1213 2005-11-05 13:03:06Z mysz $

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
$action         = empty($_GET['action']) ? '' : $_GET['action'];
$template_dir   = isset($_POST['template_dir']) ? $_POST['template_dir'] : 'main';
$tpl_dir        = isset($_GET['tpl_dir']) ? $_GET['tpl_dir'] : '';

$ft->define("editlist_templates", "editlist_templates.tpl");

$ft->define_dynamic("template_row", "editlist_templates");
$ft->define_dynamic("template_dir", "editlist_templates");



$templates_dir = ROOT . 'templates/' . $lang . '/';

switch($action) {
    
    case "add":
        $ft->assign(array(
            'READONLY' => 'readonly="readonly"',
            'RETURN_FALSE' => 'return false;'
        ));
    
        if($permarr['tpl_editor']) {
    
            $template = $_POST['template_name'];
            $text	= $_POST['text'];
        
            $tpl = $templates_dir . 'main/tpl/' . $template . '.tpl';
        
            $text = str_replace('{NOTE_ROWS}', '{ROWS}', $text);
        
            if(is_writeable($tpl)) {
            
                $fp	= fopen($tpl, 'w+');
                fwrite($fp, stripslashes($text));
                fclose($fp);
            
                $ft->assign('WRITE_ERROR', $i18n['edit_templates'][0]);
            } else {
            
                $ft->assign('WRITE_ERROR', $i18n['edit_templates'][1]);
            }
        } else {
            
            $ft->assign('WRITE_ERROR', $i18n['edit_templates'][2]);
        }
        break;
		
	case "show":

        $ft->define('form_templateedit', "form_templateedit.tpl");
        $ft->assign(array(
            'READONLY' => '',
            'RETURN_FALSE' => ''
        ));
        
        $tpl = empty($_GET['tpl']) ? '' : $_GET['tpl'];
        $template 	= ROOT . 'templates/' . $lang . '/main/tpl/' . $tpl . '.tpl';
        
        if(!is_writeable($template)) {
            $ft->assign('WRITE_ERROR', $i18n['edit_templates'][3]);
        } else {
            $ft->assign('WRITE_ERROR', '');
        }
        
        $file_content = @file_get_contents($template);
        
        if ($file_content) {
            
            // Sztywna obs³uga </textarea> w szablonie, aby by³
            // on wy¶wietlany poprawnie w polu formularza
            $file_content = str_replace('</textarea>', '&lt;/textarea>', $file_content);
            $file_content = str_replace('{ROWS}', '{NOTE_ROWS}', $file_content);
            
            // Zabronimy FT ukrywanie nie przydzielonych zmiennych
            // dziêki temu widaæ je przy edycji danego szablonu
            $ft->strict();
            
            $ft->assign(array(
                'FILE_CONTENT'	=>$file_content,
                'TEMPLATE'		=>$tpl . '.tpl',
                'TEMPLATE_NAME'	=>$tpl
            ));
        } else {
            
            $ft->assign(array(
                'FILE_CONTENT'	=> '',
                'TEMPLATE'		=> '',
                'TEMPLATE_NAME'	=> ''
            ));
        }
        break;
    default:
        $ft->assign(array(
            'READONLY' => 'readonly="readonly"',
            'RETURN_FALSE' => 'return false;'
        ));
}




$read_dir = @dir($templates_dir);

while($d = $read_dir->read()) {
    
    if($d[0] != '.') {
        $ft->assign('CURRENT_TEMPLATE', $d);
        
        if(isset($_GET['tpl_dir'])) {
            $ft->assign('SELECTED', $d == $tpl_dir ? 'selected="selected"' : '');
        } else {
            $ft->assign('SELECTED', $d == $template_dir ? 'selected="selected"' : '');
        }
        
        $ft->parse('TEMPLATE_DIR', ".template_dir");
    }
}



//lista szablonów
$path   = sprintf('../templates/%s/%s/tpl/', $lang, !isset($_GET['tpl_dir']) ? $template_dir : $tpl_dir);
$dir    = @dir($path);

while($file = $dir->read()) {
    
    $ext = str_getext($file, false);
    $filepath = $path . $file;
    $tplname = explode('.', $file);
    $tplname = $tplname[0];
    
    if(!in_array($ext, array('php', 'txt', 'html')) && is_file($filepath)) {
        
        $ft->assign(array(
            'FILE'		=>$file,
            'FILE_PATH'	=>$tplname, 
            'TPL_DIR'   =>!isset($_GET['tpl_dir']) ? $template_dir : $tpl_dir
        ));
        
        //jesli plik nie jest zapisywalny, to tpl z gwiazdka
        if(is_writeable($filepath)) {
            
            $ft->assign('STAR', '');
        } else {
            
            $ft->assign('STAR', '*');
        }
        $ft->parse('TEMPLATE_ROW', ".template_row");
    }
}

$dir->close();

$ft->parse('ROWS', "editlist_templates");

?>
