<?php

$sql = new MySQL_DB;
$sql->query("	SELECT * 
				FROM $mysql_data[db_table_category]");

while($sql->next_record()) {
	
	$c_id 	= $sql->f("category_id");
	$c_name = str_replace('&', '&amp;', $sql->f("category_name"));
	
	$ft->assign(array(	'CAT_NAME'		=>$c_name,
						'NEWS_CAT'		=>$c_id));

	$ft->parse('CATEGORY_LIST', ".category_list");
}

?>
