<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

// inicjalizacja instancji klasy DB_SQL
$db = new DB_SQL;

switch($action) {
	
	case "add":
	
		$template   = $_POST['template_name'];
		$text		= $_POST['text'];
		
		$tpl = 	'../templates/main/tpl/' . $template . '.tpl';
		
		if(is_writeable($tpl)) {
			
			$fp	= fopen($tpl, 'w+');
			fwrite($fp, stripslashes($text));
			fclose($fp);
		
			$ft->assign('CONFIRM', "Szablon zosta� Zapisany.");
		} else {
			
			$ft->assign('CONFIRM', "Nie uda�o si� edytowa� szablonu.");
		}
		
		$ft->parse('ROWS',	".result_note");
		break;
		
	case "show":

		$ft->define('form_templateedit', "form_templateedit.tpl");
			
		$tpl 		= empty($_GET['id']) ? '' : $_GET['id'];
		$template 	= get_root() . "/templates/main/tpl/" . $tpl . ".tpl";
    if (!is_writeable($template)) {
      
      $ft->assign('WRITE_ERROR', 'Brak mo�liwo�ci zapisu zmian w tym szablonie!');
    } else {

      $ft->assign('WRITE_ERROR', '');
    }
		
		$file_content = @file_get_contents($template);
    if ($file_content) {
		
      // Sztywna obs�uga </textarea> w szablonie, aby by�
      // on wy�wietlany poprawnie w polu formularza
      $file_content = str_replace('</textarea>', '&lt;/textarea>', $file_content);
      
      // Zabronimy FT ukrywanie nie przydzielonych zmiennych
      // dzi�ki temu wida� je przy edycji danego szablonu
      $ft->strict();
      
      $ft->assign(array(	'FILE_CONTENT'	=>$file_content,
                'TEMPLATE'		=>"/ " . $tpl . ".tpl",
                'TEMPLATE_NAME'	=>$tpl));
    } else {

      $ft->assign(array(	'FILE_CONTENT'	=> '',
                'TEMPLATE'		=> '',
                'TEMPLATE_NAME'	=> ''));
    }
							
		$ft->parse('ROWS',	".form_templateedit");
		break;

	default:

		$ft->define('form_templateedit', "form_templateedit.tpl");
		$ft->parse('ROWS',	".form_templateedit");

}



//lista szablon�w
if(!isset($_GET['path'])) {
    
    $path = "../templates/main/tpl/";
} else {
    
    $path = "../templates/" . $_GET['path'] . "/tpl/";
}

$dir = @dir($path);

$ft->define("editlist_templates", "editlist_templates.tpl");
$ft->define_dynamic("template_row", "editlist_templates");

while($file = $dir->read()) {
    
    $ext = str_getext($file, false);
    
    if(!in_array($ext, array('php', 'txt', 'html')) && is_file($path . $file)) {
        
        $file = explode('.', $file);
        $ft->assign(array(
            'FILE'		=>$file[0] . "." . $file[1],
            'FILE_PATH'	=>$file[0]
        ));
        
        //jesli plik nie jest zapisywalny, to tpl z gwiazdka
        if(is_writeable($path . $file)) {
            
            $ft->assign('STAR', '');
            $ft->parse('SUB_CONTENT', ".template_row");
            
        } else {
            
            $ft->assign('STAR', '*');
            $ft->parse('SUB_CONTENT', ".template_row");
        }
    }
}

$ft->parse('SUB_CONTENT', "editlist_templates");
		
$dir->close();

?>