<?php

$query = sprintf("
    SELECT
        category_id, 
        category_parent_id, 
        category_name
    FROM 
        %1\$s 
    WHERE 
        category_parent_id = '0' 
    ORDER BY 
        category_order 
    ASC",

    TABLE_CATEGORY
);

$db->query($query);

while($db->next_record()) {

    $cat_id         = $db->f("category_id");
    $cat_parent_id  = $db->f("category_parent_id");
    $cat_name       = $db->f("category_name");

    if ((bool)$rewrite) {
       $cat_link = '1,' . $cat_id . ',4,item.html';
    } else {
       $cat_link = 'index.php?p=4&amp;id=' . $cat_id . '';
    }

    $ft->assign(array(
        'CAT_NAME'  =>replace_amp($cat_name),
        'CAT_LINK'  =>$cat_link, 
        'CLASS'     =>"cat_parent", 
        'PARENT'    =>''
    ));

    $ft->define_dynamic("category_row", $assigned_tpl);

    $ft->parse('CATEGORY_ROW', ".category_row");

    // funkcja pobieraj±ca rekurencyjnie strony dziedzicz±ce::child
    $tree->get_category_cat($cat_id, 2);
}

?>
