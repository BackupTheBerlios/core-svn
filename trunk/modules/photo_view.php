<?php

$query = "	SELECT * FROM 
				$mysql_data[db_table] 
			WHERE 
				id = '$_GET[id]' 
			LIMIT 1";

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