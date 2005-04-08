<?php

if(!isset($_COOKIE['devlog_counter'])){
	
	@setcookie('devlog_counter', 'hit', time()+10800);
	
    $query = sprintf("
        UPDATE
            %1\$s
		SET
            config_value = '%2\$s'
        WHERE
            config_name = 'counter'",
            
        $mysql_data['db_table_config'],
        get_config('counter') + 1
    );
	$db->query($query);
}

?>
