<?php

$sql = new MySQL_DB;
$sql->query("	SELECT * 
				FROM $mysql_data[db_table_category]");

while($sql->next_record()) {
	
	$c_name 		= $sql->f("category_name");
	$c_id 			= $sql->f("category_id");
	
	if($c_name === "art&design") { 
		
		$c_name = "art&amp;design";
	}
	
	$ft->assign(array(	'CAT_NAME'		=>$c_name,
						'NEWS_CAT'		=>$c_id));

				
	$ft->parse('CATEGORY_LIST', ".category_list");
}

?>