<?php

$m = empty($_GET['m']) ? '' : $_GET['m'];
switch($m){
	
	case 'sign_in':
	$email = $_POST['email'];
	$data_base = new MySQL_DB;
	$data_base->query("SELECT * FROM $mysql_data[db_table_newsletter] WHERE email = '$email'");
	
	if($data_base->next_record() == 1) {
		
		$ft->assign(array(	'CONFIRM'	=>"Tw�j email znajduje si� ju� w bazie danych.",
							'STRING'	=>""));
	} else {
		
		$data_base->query("INSERT INTO $mysql_data[db_table_newsletter] VALUES('$email')");
	
		if($data_base->next_record() == 0) {
		
			$ft->assign(array(	'CONFIRM'	=>"Tw�j adres zosta� dodany do bazy danych.",
								'STRING'	=>""));
		
		} else {
		
			$ft->assign(array(	'CONFIRM'	=>"Tw�j adres nie zosta� dodany do bazy danych.",
								'STRING'	=>""));
		}
	}
	
	break;
	
	case 'sign_out':
	$email = $_POST['email'];
	$data_base = new MySQL_DB;
	$data_base->query("SELECT * FROM $mysql_data[db_table_newsletter] WHERE email = '$email'");
	
	if($data_base->next_record() == 0) {
		
		$ft->assign(array(	'CONFIRM'	=>"W bazie danych nie ma podanego przez Ciebie adresu e-mail.",
							'STRING'	=>""));
	} else {
		
		$data_base->query("DELETE FROM $mysql_data[db_table_newsletter] WHERE email = '$email'");
	
		if($data_base->next_record() == 0) {
		
			$ft->assign(array(	'CONFIRM'	=>"Tw�j adres zosta� skasowany z bazy danych.",
								'STRING'	=>""));
		
		} else {
		
			$ft->assign(array(	'CONFIRM'	=>"Tw�j adres nie zosta� skasowany z bazy danych.",
								'STRING'	=>""));
		}
	}
	
	break;
		
}
?>