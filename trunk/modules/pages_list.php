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
					
if($sql->num_rows() !== 0) {

	while($sql->next_record()) {
	
		$page_id 	= $sql->f("id");
		$parent_id 	= $sql->f("parent_id");
		$page_name 	= $sql->f("title");
	
		$ft->assign(array(	'PAGE_NAME'		=>$page_name,
							'PAGE_ID'		=>$page_id,
							'PARENT'		=>''));

				
		$ft->parse('PAGES_LIST', ".pages_list");
		
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
								'PARENT'		=>'&nbsp; &nbsp;- '));

				
			$ft->parse('PAGES_LIST', ".pages_list");
		}
	}

	$ft->parse('PAGES_HEADER', ".pages_header");
}

?>