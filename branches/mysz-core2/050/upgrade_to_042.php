<?php
// $Id: upgrade_to_042.php 1213 2005-11-05 13:03:06Z mysz $

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

require_once('inc/i18n.php');
require_once('inc/common_lib.php');

define('PATH_TO_CLASSES', get_root() . '/administration/classes');

require(PATH_TO_CLASSES . '/cls_db_mysql.php'); // dodawanie pliku konfigurujacego bibliotek� baz danych
require('administration/inc/config.php');

$db     = new DB_SQL;
$sql    = new DB_SQL;

$query = sprintf("
    CREATE TABLE IF NOT EXIST 
    %1\$s (
        id int(7) NOT NULL auto_increment, 
        news_id int(7) NOT NULL default '0', 
        category_id int(7) NOT NULL default '0', 
        PRIMARY KEY (id), 
        KEY news_id (news_id, category_id)", 

    TABLE_ASSIGN2CAT
);

$db->query($query);
$db->next_record();

$query = sprintf("
    SELECT 
        id, c_id 
    FROM 
        %1\$s", 

    TABLE_MAIN
);

$db->query($query);

while($db->next_record()) {
    
    $id     = $db->f('id');
    $c_id   = $db->f('c_id');
    
    $query = sprintf("
        INSERT INTO 
            %1\$s 
        VALUES('', '%2\$d', '%3\$d')", 
    
        TABLE_ASSIGN2CAT, 
        $id, 
        $c_id
    );
    
    $sql->query($query);
    $sql->next_record();
}

$query = sprintf("
    ALTER TABLE 
        %1\$s 
    DROP 
        c_id", 

    TABLE_MAIN
);

$db->query($query);
$db->next_record();

?>
