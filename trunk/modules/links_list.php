<?php

$query = sprintf("
    SELECT 
        title, url 
    FROM 
        %1\$s 
    ORDER BY 
        id 
    ASC", 

    $mysql_data['db_table_links']
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
    
        $ft->define("links_list", "links_list.tpl");
        $ft->define_dynamic("links_row", "links_list");
    
        $ft->parse('LINKS_LIST', ".links_row");
    }

    $ft->parse('LINKS_LIST', 'links_list');
    
}
?>