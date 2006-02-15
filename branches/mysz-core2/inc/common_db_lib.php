<?php
// $Id$

/*
 * This file is internal part of Core CMS (http://core-cms.com/) engine.
 *
 * Copyright (C) 2004-2005 Core Dev Team (more info: docs/AUTHORS).
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published
 * by the Free Software Foundation; version 2 only.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 */
    
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
