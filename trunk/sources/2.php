<?php

// kategorie
$data_base->query(" SELECT a.*, b.*, c.comments_id, count(c.id) AS comments 
					FROM $mysql_data[db_table] a, $mysql_data[db_table_category] b 
					LEFT JOIN $mysql_data[db_table_comments] c 
					ON a.id = c.comments_id
					WHERE a.c_id = '$_GET[id]' 
					AND b.category_id = '$_GET[id]' 
					AND published = 'Y' 
					GROUP BY a.date 
					DESC LIMIT $start, $mainposts_per_page");

// wpisy pojedyncze
$data_base->query(" SELECT a.*, b.*, c.comments_id, count(c.id) AS comments 
					FROM $mysql_data[db_table] a, $mysql_data[db_table_category] b 
					LEFT JOIN $mysql_data[db_table_comments] c 
					ON a.id = c.comments_id
					WHERE a.id = '$_GET[id]' 
					AND b.category_id = a.c_id 
					AND published = 'Y' 
					GROUP BY a.date 
					DESC LIMIT 1");

// strona g³ówna
$data_base->query(" SELECT a.*, b.*, c.comments_id, count(c.id) AS comments 
					FROM $mysql_data[db_table] a 
					LEFT JOIN $mysql_data[db_table_category] b 
					ON b.category_id = a.c_id 
					LEFT JOIN $mysql_data[db_table_comments] c 
					ON a.id = c.comments_id
					WHERE published = 'Y' 
					GROUP BY a.date 
					DESC LIMIT $start, $mainposts_per_page");

?>