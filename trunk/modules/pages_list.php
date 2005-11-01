<?php
// $Id$

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
        $separately = $db->f("node_separately");
        
        $ft->assign(array(
            'PAGE_NAME' =>$page_name,
            'PAGE_LINK' =>$CoreRewrite->permanent_page($page_id, $rewrite),
            'CLASS'     =>"parent",
            'PARENT'    =>''
        ));
        
        // Parsowanie nazw stron rodzicielskich::parent	
        $ft->define_dynamic("pages_row", $assigned_tpl);
    
        $ft->parse('PAGES_ROW', ".pages_row");
        
        // funkcja pobieraj±ca rekurencyjnie strony dziedzicz±ce::child
        (bool)$separately ? '' : $tree->get_cat($page_id, 2);
    }
    
} else {
    
    // swiadomie deklarowana pusta zmienna potrzebna
    // instrukcji warunkowej do przejscia do odpowiedniego miejsca szablonu
    $ft->assign('PAGE_NAME', '');
}

if(isset($_GET['id']) && $_GET['p'] == 5 || (isset($start_page_type) && $start_page_type == 'page')) {
    
    // all node pages
    $subpages = array();
    
    foreach ($pages_id as $subpage_id) {
        $subpages[] = $subpage_id;
    }
    
    function control_point($subpages, $b) {
        if ($subpages == $b) return 0;
        return ($subpages > $b) ? -1 : 1;
    }
    
    // sort - subpages[0] represent our parent
    uksort($subpages, "control_point");
    
    $query = sprintf("
        SELECT 
            node_separately 
        FROM 
            %1\$s 
        WHERE 
            id = '%2\$d' 
        AND	
            published = 'Y'", 

        TABLE_PAGES,
        $subpages[0]
    );

    $db->query($query);
    $db->next_record();
    
    $separately = $db->f('node_separately');
    
    if((bool)$separately) {
        
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
            $subpages[0]
        );
        
        $db->query($query);				
					
        if($db->num_rows() > 0) {
    
            while($db->next_record()) {
        
                $subpage_id 	= $db->f("id");
                $subparent_id 	= $db->f("parent_id");
                $subpage_name 	= $db->f("title");
        
                $ft->assign(array(
                    'SUBPAGE_NAME'  =>$subpage_name,
                    'SUBPAGE_LINK'  =>$CoreRewrite->permanent_page($subpage_id, $rewrite),
                    'CLASS'         =>"parent",
                    'PARENT'        =>''
                ));
        
                // Parsowanie nazw stron rodzicielskich::parent	
                $ft->define_dynamic("subpages_row", $assigned_tpl);
    
                $ft->parse('SUBPAGES_ROW', ".subpages_row");
        
                // funkcja pobieraj±ca rekurencyjnie strony dziedzicz±ce::child
                $tree->get_subpage_cat($subpage_id, 2);
            }
    
        } else {
            $ft->assign('SUBPAGE_NAME', '');
        }
    } else {
        $ft->assign('SUBPAGE_NAME', '');
    }
} else {
    $ft->assign('SUBPAGE_NAME', '');
}

?>
