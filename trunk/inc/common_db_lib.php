<?php
// $Id$
    
function db_get_categories() {
    global $db;

    $query = sprintf("
        SELECT 
            category_id, 
            category_parent_id, 
            category_name 
        FROM 
            %1\$s", 

        TABLE_CATEGORY
    );

    $cats = array();
    $db->query($query);
    while ($db->next_record()) {
        $cats[$db->f('category_parent_id')][$db->f('category_id')] = $db->f('category_name');
    }

    return $cats;
}

?>
