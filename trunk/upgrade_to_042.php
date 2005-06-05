<?php

require_once('inc/i18n.php');
require_once('inc/main_functions.php');

define('PATH_TO_CLASSES', get_root() . '/administration/classes');

require(PATH_TO_CLASSES . '/cls_db_mysql.php'); // dodawanie pliku konfigurujacego bibliotekê baz danych
require('administration/inc/config.php');

$db     = new DB_SQL;
$sql    = new DB_SQL;

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