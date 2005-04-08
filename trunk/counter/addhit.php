<?php

$query = sprintf("
    SELECT
        *
    FROM
        %1\$s",
      
    $mysql_data['db_table_counter']
);
$db->query($query);
$db->next_record();

$hitnumber = $db->f("hitnumber");


if(!isset($_COOKIE['devlog_counter'])){
	
	@setcookie('devlog_counter', 'hit', time()+10800);
	
    $query = sprintf("
        UPDATE
            %1\$s
		SET
            hit = '%2\$s',
            hitnumber = hitnumber+1",
            
        $mysql_data['db_table_counter'],
        $value
    );
	$db->query($query);
}

?>
