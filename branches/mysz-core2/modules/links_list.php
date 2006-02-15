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

$query = sprintf("
    SELECT 
        title, url 
    FROM 
        %1\$s 
    ORDER BY 
        link_order 
    ASC", 

    TABLE_LINKS
);

$db->query($query);
if($db->num_rows() > 0) {
    
    while($db->next_record()) {
    
        $link_name  = $db->f("title");
        $link_url   = replace_amp($db->f("url"));
    
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
