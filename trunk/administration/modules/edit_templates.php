<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

// inicjalizacja instancji klasy MySQL_DB
$db = new MySQL_DB;

switch($action) {
	
	case "add":
	
		$ft->define('form_templateedit', "form_templateedit.tpl");
		$ft->parse('ROWS',	".form_templateedit");
		break;
		
	case "show":
			
		$tpl 		= empty($_GET['id']) ? '' : $_GET['id'];
		$template 	= "../templates/main/tpl/" . $tpl . ".tpl";
		
		$file_content = file_get_contents($template);
		
		$ft->STRICT = true;
		$ft->assign(array(	'FILE_CONTENT'	=>$file_content,
							'TEMPLATE'		=>"/ " . $tpl . ".tpl"));
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
			
	if(!ereg(".php", $file) && !ereg(".txt", $file) && !ereg(".html", $file) && $file != '.' && $file != '..') {
				
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