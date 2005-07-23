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

    TABLE_PAGES,
    0
);

$db->query($query);				
					
if($db->num_rows() > 0) {
    
    while($db->next_record()) {
        
        $page_id 	= $db->f("id");
        $parent_id 	= $db->f("parent_id");
        $page_name 	= $db->f("title");
        
        $page_link  = (bool)$rewrite ? sprintf('1,%s,5,item.html', $page_id) : 'index.php?p=5&amp;id=' . $page_id;

        
        $ft->assign(array(
            'PAGE_NAME' =>$page_name,
            'PAGE_LINK' =>$page_link,
            'CLASS'     =>"parent",
            'PARENT'    =>''
        ));
        
        // Parsowanie nazw stron rodzicielskich::parent	
        $ft->define_dynamic("pages_row", $assigned_tpl);
    
        $ft->parse('PAGES_ROW', ".pages_row");
        
        // funkcja pobieraj±ca rekurencyjnie strony dziedzicz±ce::child
        $tree->get_cat($page_id, 2);
    }
    
} else {
    
    // swiadomie deklarowana pusta zmienna potrzebna
    // instrukcji warunkowej do przejscia do odpowiedniego miejsca szablonu
    $ft->assign('PAGE_NAME', '');
}

?>