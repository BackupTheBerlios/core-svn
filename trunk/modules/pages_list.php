<?php

$query = sprintf("
    SELECT 
        id, parent_id, title 
    FROM 
        %1\$s 
    WHERE 
        parent_id = '%2\$d' 
    AND	
        published = 'Y' 
    ORDER BY 
        id 
    ASC", 

    $mysql_data['db_table_pages'],
    0
);

$db->query($query);

$ft->define(array(
    'pages_header'  =>"pages_header.tpl",
    'pages_parent'  =>"pages_parent.tpl",
    'pages_list'    =>"pages_list.tpl"
));					
					
if($db->num_rows() > 0) {
    
    while($db->next_record()) {
        
        $page_id 	= $db->f("id");
        $parent_id 	= $db->f("parent_id");
        $page_name 	= $db->f("title");
        
        $ft->assign(array(
            'PAGE_NAME' =>$page_name,
            'PAGE_ID'   =>$page_id
        ));
        
        // Parsowanie nazw stron rodzicielskich::parent	
        $ft->parse('PAGES_LIST', ".pages_parent");
        
        // funkcja pobieraj±ca rekurencyjnie strony dziedzicz±ce::child
        get_cat($page_id, 2);
    }
    
    $ft->parse('PAGES_HEADER', ".pages_header");
}

?>