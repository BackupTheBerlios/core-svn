<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

// inicjalizacja instancji klasy MySQL_DB
$db = new MySQL_DB;

switch($action) {
	
	case "add":
	
		$template   = $_POST['template_name'];
		$text		= $_POST['text'];
		
		$fp		= @fopen('../templates/main/tpl/' . $template . '.tpl', 'w');
		$result = @fputs($fp, $text, strlen($text));
		@fclose($fp);
	
		$ft->assign('CONFIRM', "Szablon zosta� Zapisany.");
		$ft->parse('ROWS',	".result_note");
		break;
		
	case "show":
			
		$tpl 		= empty($_GET['id']) ? '' : $_GET['id'];
		$template 	= "../templates/main/tpl/" . $tpl . ".tpl";
		
		$file_content = file_get_contents($template);
		
		// Sztywna obs�uga </textarea> w szablonie, aby by�
		// on wy�wietlany poprawnie w polu formularza
		$file_content = str_replace('</textarea>', '&lt;/textarea>', $file_content);
		
		// Zabronimy FT ukrywanie nie przydzielonych zmiennych
		// dzi�ki temu wida� je przy edycji danego szablonu
		$ft->STRICT = true;
		
		$ft->assign(array(	'FILE_CONTENT'	=>$file_content,
							'TEMPLATE'		=>"/ " . $tpl . ".tpl",
							'TEMPLATE_NAME'	=>$tpl));
							
		$ft->define('form_templateedit', "form_templateedit.tpl");
		$ft->parse('ROWS',	".form_templateedit");
		break;

	default:

		$ft->define('form_templateedit', "form_templateedit.tpl");
		$ft->parse('ROWS',	".form_templateedit");

}

if(!isset($_GET['path'])) {
			
	$path = "../templates/main/tpl/";
} else {
			
	$path = "../templates/" . $_GET['path'] . "/tpl/";
}
		
$dir = @dir($path);
while($file = $dir->read()) {
			
	if(!ereg(".php", $file) 
		&& !ereg(".txt", $file) 
		&& !ereg(".html", $file) 
		&& $file != '.' 
		&& $file != '..') {
				
		$file = explode('.', $file);
				
		$ft->assign(array(	'FILE'		=>$file[0] . "." . $file[1],
							'FILE_PATH'	=>$file[0]));
									
		$ft->define('filelist', "filelist.tpl");
		$ft->parse('FILEBOX_CONTENT', ".filelist");
	}
}
		
$ft->define('filebox', "filebox.tpl");
$ft->parse('SUB_CONTENT', ".filebox");
		
$dir->close();
?>