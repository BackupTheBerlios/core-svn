<?php

$sql = new MySQL_DB;
$sql->query("	SELECT id, title 
				FROM $mysql_data[db_table_pages] 
				WHERE published = 'Y' 
				ORDER BY id ASC");

$ft->define(array(	'pages_header'	=>"pages_header.tpl",
					'pages_list'	=>"pages_list.tpl"));
					
if($sql->num_rows() !== 0) {

	while($sql->next_record()) {
	
		$page_id 	= $sql->f("id");
		$page_name 	= $sql->f("title");
	
		$ft->assign(array(	'PAGE_NAME'		=>$page_name,
							'PAGE_ID'		=>$page_id));

				
		$ft->parse('PAGES_LIST', ".pages_list");
	}

	$ft->parse('PAGES_HEADER', ".pages_header");
}

?>