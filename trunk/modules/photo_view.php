<?php

$query = sprintf("
			SELECT * FROM 
				$mysql_data[db_table] 
			WHERE 
				id = '%1\$d' 
			LIMIT 1", $_GET['id']);

$db->query($query);
$db->next_record();

$image	= $db->f("image");

list($width, $height) = getimagesize("photos/" . $image);

$ft->assign(array(
				'IMAGE_NAME'	=>$image,
				'IMAGE_WIDTH'	=>$width,
				'IMAGE_HEIGHT'	=>$height
));

$ft->define('photo_view', "photo_view.tpl");
$ft->parse('ROWS', ".photo_view");

?>