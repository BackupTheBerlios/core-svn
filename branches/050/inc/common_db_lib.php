<?php
// $Id: common_db_lib.php 1128 2005-08-03 22:16:55Z mysz $
    
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
