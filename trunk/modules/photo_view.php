<?php

$query = sprintf("
    SELECT * FROM 
        %1\$s 
    WHERE 
        id = '%2\$d' 
    LIMIT 1", 

    $mysql_data['db_table'],
    $_GET['id']
);

$db->query($query);
$db->next_record();

$image  = $db->f("image");

list($width, $height) = getimagesize("photos/" . $image);

$ft->assign(array(
    'IMAGE_NAME'    =>$image,
    'IMAGE_WIDTH'   =>$width,
    'IMAGE_HEIGHT'  =>$height
));

$ft->define('photo_view', "photo_view.tpl");
$ft->parse('ROWS', ".photo_view");

?>