<?php

// Inicjowanie egzemplarza klasy do obs³ugi Bazy Danych
$db = new MySQL_DB;

// Zliczenie wszystkich publikowanych wpisów
$query = "	SELECT 
				count(*) AS id 
			FROM 
				$mysql_data[db_table] 
			WHERE 
				published = 'Y' 
			ORDER BY 
				date";

$db->query($query);
$db->next_record();
$published_items 	= $db->f("id");

// Zliczenie wszystkich nie publikowanych wpisów
$query = "	SELECT 
				count(*) AS id 
			FROM 
				$mysql_data[db_table] 
			WHERE 
				published = 'N' 
			ORDER BY 
				date";

$db->query($query);
$db->next_record();
$nonpublished_items 	= $db->f("id");

// Zliczenie wszystkich wpisów
$num_items 	= $published_items + $nonpublished_items;

$ft->assign(array(	'COUNT_NOTES'		=>$num_items,
					'PUBLISHED_NOTES'	=>$published_items,
					'NONPUBLISHED_NOTES'=>$nonpublished_items));
					
$ft->parse('ROWS', ".main_site");
?>