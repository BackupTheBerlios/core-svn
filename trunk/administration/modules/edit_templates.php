<?php

// deklaracja zmiennej $action::form
$action         = empty($_GET['action']) ? '' : $_GET['action'];
$template_dir   = isset($_POST['template_dir']) ? $_POST['template_dir'] : 'main';
$tpl_dir        = isset($_GET['tpl_dir']) ? $_GET['tpl_dir'] : '';

$ft->define("editlist_templates", "editlist_templates.tpl");

$ft->define_dynamic("template_row", "editlist_templates");
$ft->define_dynamic("template_dir", "editlist_templates");

switch($action) {
    
    case "add":
    
        if($permarr['tpl_editor']) {
    
            $template   = $_POST['template_name'];
            $text		= $_POST['text'];
        
            $tpl = 	'../templates/main/tpl/' . $template . '.tpl';
        
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
        
        $tpl 		= empty($_GET['tpl']) ? '' : $_GET['tpl'];
        $template 	= get_root() . "/templates/main/tpl/" . $tpl . ".tpl";
        
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
                'TEMPLATE'		=>"/ " . $tpl . ".tpl",
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

}

$templates_dir = '../templates/';
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
$path   = !isset($_GET['tpl_dir']) ? "../templates/" . $template_dir . "/tpl/" : "../templates/" . $tpl_dir . "/tpl/";
$dir    = @dir($path);

while($file = $dir->read()) {
    
    $ext = str_getext($file, false);
    
    if(!in_array($ext, array('php', 'txt', 'html')) && is_file($path . $file)) {
        
        $file = explode('.', $file);
        $ft->assign(array(
            'FILE'		=>$file[0] . "." . $file[1],
            'FILE_PATH'	=>$file[0], 
            'TPL_DIR'   =>!isset($_GET['tpl_dir']) ? $template_dir : $tpl_dir
        ));
        
        //jesli plik nie jest zapisywalny, to tpl z gwiazdka
        if(is_writeable($path . $file)) {
            
            $ft->assign('STAR', '');
            $ft->parse('TEMPLATE_ROW', ".template_row");
        } else {
            
            $ft->assign('STAR', '*');
            $ft->parse('TEMPLATE_ROW', ".template_row");
        }
    }
}

$ft->parse('ROWS', "editlist_templates");
		
$dir->close();

?>