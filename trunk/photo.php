<?php
define("PATH_TO_CLASSES", "administration/classes");

require(PATH_TO_CLASSES . "/cls_db_mysql.inc"); // dodawanie pliku konfigurujacego bibliotekę baz danych
require("administration/inc/config.php");

$data_base = new MySQL_DB;
$data_base->query("SELECT * FROM $mysql_data[db_table] WHERE id='$_GET[id]' LIMIT 1");

$data_base->next_record();

$image			= $data_base->f("image");

list($width, $height) = getimagesize("photos/" . $image);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>./DEV-LOG</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<link rel="stylesheet" type="text/css" href="style/style.css">
</head>

<body style="margin: 0px; BACKGROUND-COLOR: #FFF;">
  <table width="<?=$width;?>" height="<?=$height;?>" cellspacing="0" cellpadding="0" border="0" align="center">
	<tr>
	  <td><?php echo "<a href=\"#\" onclick=\"javascript:window.close();\"><img src=\"photos/" . $image . "\" width=\"" . $width . "\" height=\"" . $height . "\"></a>"; ?></td>
	</tr>
  </table>
</body>
</html>
