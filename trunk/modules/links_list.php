<?php

$query = "  SELECT 
                title, url 
            FROM 
                $mysql_data[db_table_links] 
            ORDER BY 
                id 
            ASC";

$db->query($query);

$ft->define('links_list', "links_list.tpl");
	
while($db->next_record()) {
	
	$link_name 	= $db->f("title");
	$link_url	= str_replace('&', '&amp;', $db->f("url"));
	
	$ft->assign(array(
        'LINK_NAME' =>$link_name,
        'LINK_URL'  =>$link_url
    ));
				
	$ft->parse('LINKS_LIST', ".links_list");
}
?>