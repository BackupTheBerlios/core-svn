<?php

$sql = new MySQL_DB;
$query = "	SELECT 
				id, parent_id, title 
			FROM 
				$mysql_data[db_table_pages] 
			WHERE 
				parent_id = '0' 
			AND	
				published = 'Y' 
			ORDER BY 
				id 
			ASC";

$sql->query($query);

$ft->define(array(	'pages_header'	=>"pages_header.tpl",
					'pages_list'	=>"pages_list.tpl"));
					
function get_cat($page_id, $separator) {
	
	global $mysql_data, $ft;

	$query = "	SELECT 
					id, parent_id, title 
				FROM 
					$mysql_data[db_table_pages] 
				WHERE 
					parent_id = '$page_id' 
				AND 
					published = 'Y' 
				ORDER BY 
					id 
				ASC";

	$db = new MySQL_DB;
	$db->query($query);
		
	while($db->next_record()) {
	
		$page_id 	= $db->f("id");
		$parent_id 	= $db->f("parent_id");
		$page_name 	= $db->f("title");
	
		$ft->assign(array(	'PAGE_NAME'		=>$page_name,
							'PAGE_ID'		=>$page_id,
							'PARENT'		=>$separator));

				
		$ft->parse('PAGES_LIST', ".pages_list");
		get_cat($page_id, ' &nbsp; &nbsp;- ');
	}
}
					
if($sql->num_rows() > 0) {

	while($sql->next_record()) {
	
		$page_id 	= $sql->f("id");
		$parent_id 	= $sql->f("parent_id");
		$page_name 	= $sql->f("title");
	
		$ft->assign(array(	'PAGE_NAME'		=>$page_name,
							'PAGE_ID'		=>$page_id,
							'PARENT'		=>''));
				
		$ft->parse('PAGES_LIST', ".pages_list");
		
		// funkcja pobieraj±ca rekurencyjnie kategorie
		get_cat($page_id, ' &nbsp; &nbsp;- ');
	}

	$ft->parse('PAGES_HEADER', ".pages_header");
}

?>
