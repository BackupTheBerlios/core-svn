<?php
// $Id$

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

    $ft->assign(array(
        'CAT_NAME'  =>replace_amp($cat_name),
        'CAT_LINK'  =>$CoreRewrite->category_news($cat_id, $rewrite), 
        'CLASS'     =>"cat_parent", 
        'PARENT'    =>''
    ));

    $ft->define_dynamic("category_row", $assigned_tpl);

    $ft->parse('CATEGORY_ROW', ".category_row");

    // funkcja pobieraj±ca rekurencyjnie strony dziedzicz±ce::child
    $tree->get_category_cat($cat_id, 2);
}

?>
