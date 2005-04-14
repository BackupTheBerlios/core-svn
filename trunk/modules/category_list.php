<?php

$query = sprintf("
    SELECT
        *
    FROM 
        %1\$s",

    $mysql_data['db_table_category']
);

$db->query($query);

while($db->next_record()) {
    
    $ft->assign(array(
        'CAT_NAME'  => str_replace('&', '&amp;', $db->f('category_name')),
        'NEWS_CAT'  => $db->f('category_id')
    ));
    
    $ft->define("category_list", "category_list.tpl");
    $ft->define_dynamic("category_row", "category_list");
    
    $ft->parse('CATEGORY_LIST', ".category_row");
    
}

$ft->parse('CATEGORY_LIST', 'category_list');
?>