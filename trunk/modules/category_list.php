<?php

$query = sprintf("
    SELECT
        category_id, 
        category_parent_id, 
        category_name
    FROM 
        %1\$s 
    WHERE 
        category_parent_id = '%2\$d' 
    ORDER BY 
        category_order 
    ASC",

    $mysql_data['db_table_category'],
    0
);

$db->query($query);

while($db->next_record()) {
    
    $cat_id         = $db->f("category_id");
    $cat_parent_id  = $db->f("category_parent_id");
    $cat_name       = $db->f("category_name");
    
    $cat_link       = isset($rewrite) && $rewrite == 1 ? '1,' . $cat_id . ',4,item.html' : 'index.php?p=4&amp;id=' . $cat_id . '';
    
    $ft->assign(array(
        'CAT_NAME'  =>str_replace('&', '&amp;', $cat_name),
        'CAT_LINK'  =>$cat_link, 
        'CLASS'     =>"cat_parent", 
        'PARENT'    =>''
    ));
    
    $ft->define_dynamic("category_row", "main");
    
    $ft->parse('CATEGORY_ROW', ".category_row");
    
    // funkcja pobieraj±ca rekurencyjnie strony dziedzicz±ce::child
    get_category_cat($cat_id, 2);
    
}

?>