<?php
if (!$permarr['moderator']) {
  if ($permarr['writer']) {
    header('Location: main.php?p=13');
    exit;
  }
  header('Location: main.php');
  exit;
}

$action = empty($_GET['action']) ? '' : $_GET['action'];

// definicja szablonow parsujacych wyniki bledow.
$ft->define("error_reporting", "error_reporting.tpl");
$ft->define_dynamic("error_row", "error_reporting");

switch ($action) {
    
	case "add":
      $monit    = array();

      if(strlen($_POST['login_name']) < 4) {
        $monit[] = $i18n['add_user'][0];
      }
      if(strlen($_POST['password']) < 6) {
        $monit[] = $i18n['add_user'][2];
      }
      if(!check_mail($_POST['email'])) {
        $monit[] = $i18n['add_user'][1];
      }
      if($_POST['password'] != $_POST['password_repeat']) {
        $monit[] = $i18n['add_user'][3];
      }

      $query	= sprintf("
          SELECT * FROM 
              %1\$s 
          WHERE 
              login = '%2\$s'", 
  
          TABLE_USERS, 
          $_POST['login_name']
      );
      $db->query($query);
  
      if($db->next_record() > 0) {
          $monit[] = $i18n['add_user'][5];
      }

      if(!empty($monit)) {
          foreach ($monit as $error) {
              $ft->assign('ERROR_MONIT', $error);
              $ft->parse('ROWS',	".error_row");
          }
          $ft->parse('ROWS', "error_reporting");
      } else {
          $query = sprintf("
              INSERT INTO 
                  %1\$s 
              VALUES 
                  ('', '%2\$s', '%3\$s', '%4\$s', '%5\$d',
                  'Y', '%6\$s', '%7\$s', '%8\$s', '%9\$s',
                  '%10\$s', '%11\$s', '%12\$s', '%13\$s',
                  '%14\$s', '%15\$s')",

              TABLE_USERS,
              $_POST['login_name'],
              md5($_POST['password']),
              $_POST['email'],
              1,
              $_POST['name'],
              $_POST['surname'],
              $_POST['city'],
              $_POST['country'],
              $_POST['www'],
              $_POST['gg'],
              $_POST['tlen'],
              $_POST['jid'],
              $_POST['hobby'],
              $_POST['additional_inf']
          );
      
          $db->query($query);

          $ft->assign('CONFIRM', $i18n['add_user'][4]);
          $ft->parse('ROWS', ".result_note");
      }
		break;

	default:
			
		$ft->define('form_useradd', "form_useradd.tpl");
		$ft->parse('ROWS', ".form_useradd");
		break;
}

?>
