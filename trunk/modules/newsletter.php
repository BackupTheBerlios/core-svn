<?php

$m	= empty($_GET['m']) ? '' : $_GET['m'];
$db = new MySQL_DB;

switch($m){
	
	case 'sign_in':
		$email	= $_POST['email'];
	
		$query	= sprintf("	
					SELECT * FROM 
						$mysql_data[db_table_newsletter] 
					WHERE 
						email = '%1\$s'", $email);
		$db->query($query);
	
		if($db->next_record() > 0) {
		
			$ft->assign(array(	'CONFIRM'	=>"Twój email znajduje siê ju¿ w bazie danych.",
								'STRING'	=>""));
		} else {
		
			$query	= "	INSERT INTO 
							$mysql_data[db_table_newsletter] 
						VALUES('$email')";
			$db->query($query);
	
			if($db->next_record() == 0) {
		
				$ft->assign(array(	'CONFIRM'	=>"Twój adres zosta³ dodany do bazy danych.",
									'STRING'	=>""));
		
			} else {
		
				$ft->assign(array(	'CONFIRM'	=>"Twój adres nie zosta³ dodany do bazy danych.",
									'STRING'	=>""));
			}
		}
	
	break;
	
	case 'sign_out':
	
		if (isset($_POST['email'])) {
			
			$email	= $_POST['email'];
		} else {
			$email = '';
		}
	
		$query	= sprintf("	
					SELECT * FROM 
						$mysql_data[db_table_newsletter] 
					WHERE 
						email = '%1\$s'", $email);
		$db->query($query);
	
		if($db->next_record() == 0) {
		
			$ft->assign(array(	'CONFIRM'	=>"W bazie danych nie ma podanego przez Ciebie adresu e-mail.",
								'STRING'	=>""));
		} else {
		
			$query	= sprintf("	
						DELETE FROM 
							$mysql_data[db_table_newsletter] 
						WHERE 
							email = '%1\$s'", $email);
			$db->query($query);
	
			if($db->next_record() == 0) {
		
				$ft->assign(array(	'CONFIRM'	=>"Twój adres zosta³ skasowany z bazy danych.",
									'STRING'	=>""));
			} else {
		
				$ft->assign(array(	'CONFIRM'	=>"Twój adres nie zosta³ skasowany z bazy danych.",
									'STRING'	=>""));
			}
		}
	
		break;	
}
?>