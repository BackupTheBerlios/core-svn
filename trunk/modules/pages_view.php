<?php

$data_base = new MySQL_DB;
$data_base->query("	SELECT * 
					FROM $mysql_data[db_table_pages] 
					WHERE id='$_GET[id]' 
					AND published = 'Y' 
					LIMIT 1");

if($data_base->num_rows() !== 0) {

	$data_base->next_record();

	$title 			= $data_base->f("title");
	$text 			= $data_base->f("text");
	$id 			= $data_base->f("id");
	$image			= $data_base->f("image");
	
	$ft->assign(array(	'PAGE_TITLE'		=>ucfirst(strtolower($title)),
						'PAGE_TEXT'			=>$text,
						'PAGE_ID'			=>$id));
	
	if(empty($image)) {

		$ft->assign(array('IMAGE' =>""));
	} else {
		
		list($width, $height) = getimagesize("photos/" . $image);
		
		// wysoko¶æ, szeroko¶æ obrazka
		$ft->assign(array(	'WIDTH'		=>$width,
							'HEIGHT'	=>$height));
		
		if($width > 440) {
			
			// template prepare
			$ft->define('image_alter', "image_alter.tpl");
			$ft->assign('UID', $id);
				
			$ft->parse('IMAGE', "image_alter");
		} else {
			
			// template prepare
			$ft->define('image_main', "image_main.tpl");
			$ft->assign('IMAGE_NAME', $image);

			$ft->parse('IMAGE', "image_main");
		}
	}
	
	// template prepare
	$ft->define('pages_view', "pages_view.tpl");

	$ft->parse('ROWS',".pages_view");
} else {
	
	$ft->assign(array(	'QUERY_FAILED'	=>"W bazie danych nie ma wpisu o ¿±danym id",
						'STRING'			=>""));
	
	$ft->parse('ROWS',".query_failed");
}

?>