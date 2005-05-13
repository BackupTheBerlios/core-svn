<?php

$query = sprintf("
    SELECT 
        title, url 
    FROM 
        %1\$s 
    ORDER BY 
        id 
    ASC", 

    TABLE_LINKS
);

$db->query($query);
if($db->num_rows() > 0) {
    
    while($db->next_record()) {
    
        $link_name  = $db->f("title");
        $link_url   = str_replace('&', '&amp;', $db->f("url"));
    
        $ft->assign(array(
            'LINK_NAME' =>$link_name,
            'LINK_URL'  =>$link_url
        ));

        $ft->define_dynamic("links_row", $assigned_tpl);
    
        $ft->parse('LINKS_ROW', ".links_row");
    }
} else {
    
    // swiadomie deklarowana pusta zmienna potrzebna
    // instrukcji warunkowej do przejscia do odpowiedniego miejsca szablonu
    $ft->assign('LINK_NAME', '');
}

?>