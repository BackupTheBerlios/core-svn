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
		
			$ft->assign('CONFIRM', "Szablon zosta³ Zapisany.");
		} else {
			
			$ft->assign('CONFIRM', "Nie uda³o siê edytowaæ szablonu.");
		}
		
		$ft->parse('ROWS',	".result_note");
		break;
		
	case "show":

		$ft->define('form_templateedit', "form_templateedit.tpl");
			
		$tpl 		= empty($_GET['id']) ? '' : $_GET['id'];
		$template 	= get_root() . "/templates/main/tpl/" . $tpl . ".tpl";
    if (!is_writeable($template)) {
      
      $ft->assign('WRITE_ERROR', 'Brak mo¿liwo¶ci zapisu zmian w tym szablonie!');
    } else {

      $ft->assign('WRITE_ERROR', '');
    }
		
		$file_content = @file_get_contents($template);
    if ($file_content) {
		
      // Sztywna obs³uga </textarea> w szablonie, aby by³
      // on wy¶wietlany poprawnie w polu formularza
      $file_content = str_replace('</textarea>', '&lt;/textarea>', $file_content);
      
      // Zabronimy FT ukrywanie nie przydzielonych zmiennych
      // dziêki temu widaæ je przy edycji danego szablonu
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



//lista szablonów
if(!isset($_GET['path'])) {
			
	$path = "../templates/main/tpl/";
} else {
			
	$path = "../templates/" . $_GET['path'] . "/tpl/";
}

$dir = @dir($path);
while($file = $dir->read()) {
  $ext = str_getext($file, false);
  
  if(
      !in_array($ext, array('php', 'txt', 'html'))
      //&& !in_array($file, array('.', '..', '.svn'))
      && is_file($path . $file)
  ) {
		$file = explode('.', $file);
				
		$ft->assign(array(	'FILE'		=>$file[0] . "." . $file[1],
							'FILE_PATH'	=>$file[0]));
									
    
    //jesli plik nie jest zapisywalny, to tpl z gwiazdka
    if (is_writeable($path . $file)) {

      $ft->define('filelist', "filelist.tpl");
    } else {

      $ft->define('filelist', "filelist_closed.tpl");
    }
      
    
		$ft->parse('FILEBOX_CONTENT', ".filelist");
	}
}
		
$ft->define('filebox', "filebox.tpl");
$ft->parse('SUB_CONTENT', ".filebox");
		
$dir->close();
?>
