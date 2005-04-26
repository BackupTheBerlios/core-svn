<?php

$query = sprintf("
    SELECT * FROM 
        %1\$s 
    WHERE 
        parent_id = '%2\$d' 
    AND	
        published = 'Y' 
    ORDER BY 
        page_order 
    ASC", 

    $mysql_data['db_table_pages'],
    0
);

$db->query($query);				
					
if($db->num_rows() > 0) {
    
    while($db->next_record()) {
        
        $page_id 	= $db->f("id");
        $parent_id 	= $db->f("parent_id");
        $page_name 	= $db->f("title");
        
        $page_link  = isset($rewrite) && $rewrite == 1 ? '1,' . $page_id . ',5,item.html' : 'index.php?p=5&amp;id=' . $page_id . '';
        
        $ft->assign(array(
            'PAGE_NAME' =>$page_name,
            'PAGE_LINK' =>$page_link,
            'CLASS'     =>"parent",
            'PARENT'    =>''
        ));
        
        // Parsowanie nazw stron rodzicielskich::parent	
        $ft->define_dynamic("pages_row", "main");
    
        $ft->parse('PAGES_ROW', ".pages_row");
        
        // funkcja pobieraj±ca rekurencyjnie strony dziedzicz±ce::child
        get_cat($page_id, 2);
    }
    
}

?>