<?php

function main_pagination($url, $q, $p, $published, $table) {
	
	global $start, $days_to, $mysql_data, $mainposts_per_page, $page_string;
	
	// Egzemplarz klasy obs³uguj±cej konfiguracjê wy¶wietlanych wpisów
	$data_base_config = new MySQL_DB;
	$data_base_config->query("SELECT * FROM $mysql_data[db_table_config] WHERE config_name = '$p'");
	$data_base_config->next_record();
		
	$mainposts_per_page = $data_base_config->f("config_value");
	// Egzemplarz klasy obs³ugujacy stronnicowanie
	$data_b = new MySQL_DB;
	$data_b->query("SELECT count(*) AS id 
					FROM $mysql_data[$table] 
					WHERE $q 
					TO_DAYS(NOW()) - TO_DAYS(date) <= $days_to $published 
					ORDER BY date");
	
	$data_b->next_record();
	$num_items 	= $data_b->f("0");

	$total_pages = empty($total_pages) ? '' : $total_pages;
	
	// Obliczanie liczby stron
	if ($num_items > $mainposts_per_page) {	
		
		$total_pages = ceil($num_items/$mainposts_per_page);
	}
	
	if ( $total_pages == 1 ) {
		
		echo '';
	}
	
	// Obliczanie strony, na której obecnie jestesmy
	$on_page = floor($start / $mainposts_per_page) + 1;

	$page_string = '';
	
	if ( $total_pages > 6 ) {
		
		$init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;

		for($i = 1; $i < $init_page_max + 1; $i++) {
			
			$page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>' : '<a href="' . $url . ( ( $i - 1 ) * $mainposts_per_page ) . '">' . $i . '</a>';
			
			if ( $i <  $init_page_max ) {
				
				$page_string .= ", ";
			}
		}

		if ( $total_pages > 3 ) {
			
			if ( $on_page > 1  && $on_page < $total_pages ) {
				
				$page_string .= ( $on_page > 5 ) ? ' ... ' : ', ';

				$init_page_min = ( $on_page > 4 ) ? $on_page : 5;
				$init_page_max = ( $on_page < $total_pages - 4 ) ? $on_page : $total_pages - 4;

				for($i = $init_page_min - 1; $i < $init_page_max + 2; $i++) {
					
					$page_string .= ($i == $on_page) ? '<b>' . $i . '</b>' : '<a href="' . $url . ( ( $i - 1 ) * $mainposts_per_page ) . '">' . $i . '</a>';
					
					if ( $i <  $init_page_max + 1 ) {
						
						$page_string .= ', ';
					}
				}

				$page_string .= ( $on_page < $total_pages - 4 ) ? ' ... ' : ', ';
			
			} else {
				
				$page_string .= ' ... ';
			}

			for($i = $total_pages - 2; $i < $total_pages + 1; $i++) {
				
				$page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>'  : '<a href="' . $url . ( ( $i - 1 ) * $mainposts_per_page ) . '">' . $i . '</a>';
				
				if( $i <  $total_pages ) {
					
					$page_string .= ", ";
				}
			}
		}
	} else {
		
		for($i = 1; $i < $total_pages + 1; $i++) {
			
			$page_string .= ( $i == $on_page ) ? '<b>' . $i . '</b>' : '<a href="' . $url . ( ( $i - 1 ) * $mainposts_per_page ) . '">' . $i . '</a>';
			
			if ( $i <  $total_pages ) {
				
				$page_string .= ', ';
			}
		}
	}


	if ( $on_page > 1 ) {
			
		$page_string = ' <a href="' . $url . ( ( $on_page - 2 ) * $mainposts_per_page ) . '">' . " <b>\$i--</b>" . '</a>&nbsp;&nbsp;' . $page_string;
	}

	if ( $on_page < $total_pages ) {
			
		$page_string .= '&nbsp;&nbsp;<a href="' . $url . ( $on_page * $mainposts_per_page ) . '">' . "<b>\$i++</b> " . '</a>';
	}
}

?>