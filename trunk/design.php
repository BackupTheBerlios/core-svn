<?php

if(isset($_GET['issue']) && is_dir('./templates/' . $_GET['issue'] . '/tpl/')){
			
	$theme = $_GET['issue'];
} else {

	$theme = 'main';
}

@setcookie('devlog_design', $theme, time() + 3600 * 24 * 365);

header("Location: http://".$_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']));
exit;
?>
