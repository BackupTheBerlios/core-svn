<?php
// $Id$

header('Content-type: text/html; charset=UTF8');

/*
 * IMPORTANT: do not change include to require!
 *
 */
@include_once('inc/config.php');
if(!defined('CORE_INSTALLED')) {

    header('Location: ../install/install.php');
    exit;
}



//czyscimy katalog tymczasowy
$dh = dir(TMPDIR);
while (($obj = $dh->read()) !== false) {
    $path = TMPDIR . $obj;
    if (is_file($path) &&
            !in_array($obj, array('.', '..')) &&
            fileatime($path) < (time() - 3600*24) ) {
        @unlink($path);
    }
}



session_register('login');
session_register('loggedIn');

/*
 * TODO:
 * w sesji przechowywa� login i hash has�a. przy ka�dym wej�ciu
 * musi by� sprawdzana poprawno��. inaczej, je�li kto� si� nie 
 * b�dzie wylogowywa� wystaczaj�co d�ugo, mo�e to spowodowa� problemy z
 * bezpiecze�stwem (wy��czenie/skasowanie usera nie spowoduje braku mo�liwo�ci
 * namieszania przez niego w systemie)
 *
 */
if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === TRUE){
    
    header('Location: main.php');
    break;
}

require_once(PATH_TO_CLASSES. '/cls_db_mysql.php');
require_once(PATH_TO_CLASSES. '/cls_phpmailer.php');

require_once(ROOT . 'inc/common_lib.php');

// mysql_server_version
get_mysql_server_version();

$lang = get_config('language_set');

require_once('i18n/' . $lang . '/i18n.php');
require_once(PATH_TO_CLASSES . '/cls_fast_template.php');

// warto�� pocz�tkowa zmiennej $start -> potrzebna przy stronnicowaniu
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;

// inicjowanie klasy, wkazanie katalogu przechowuj�cego szablony
$ft = new FastTemplate('./templates/' . $lang . '/tpl');

$ft->define(array(
    'main'          =>'main.tpl',
    'main_loader'   =>'main_loader.tpl',
    'rows'          =>'rows.tpl',
    'form_login'    =>'form_login.tpl'
));
        
$ft->assign(array(
    'TITLE'         =>$i18n['index'][0],
    'ERROR_MSG'     =>'', 
    'LANG'          =>$lang
));

// deklaracja zmiennej $p
$p = empty($_GET['p']) ? '' : $_GET['p'];

if($p == 'log') {
    
    $login       = trim($_POST['login']);
    $password    = trim(md5($_POST['password']));
    
    if(empty($login) || empty($password)) {
        
        // U�ytkownik nie uzupe�ni� wszystkich p�l::form
        $ft->assign('ERROR_MSG', $i18n['index'][1]);
        $ft->parse('ROWS', '.form_login');
    } else {
        
        $db = new DB_SQL;
        $query = sprintf("
            SELECT 
                active 
            FROM 
                %1\$s 
            WHERE 
                login = '%2\$s' 
            AND 
                password = '%3\$s'", 
        
            TABLE_USERS, 
            $login, 
            $password
        );
        
        $db->query($query);
    
        if($db->num_rows()) {

            if($db->f('active') != 'N') {
                
                // Rejestrujemy zmienne sesyjne
                $_SESSION['login']       = $login;
                $_SESSION['loggedIn']    = TRUE;
        
                header('Location: main.php');
                break;
            } else {
                
                // U�ytkownik nie zaaktywowa� konta::db
                $ft->assign('ERROR_MSG', $i18n['index'][2]);
                $ft->parse('ROWS', '.form_login');
            }
        } else {
            // Niepoprawne dane wej�cia<->wyj�cia::form, db
            $ft->assign('ERROR_MSG', $i18n['index'][3]);
            $ft->parse('ROWS', '.form_login');
        }
    }
} else {
    include(ROOT . 'administration/modules/login.php');
    
}

$ft->parse('MAIN', array('main_loader', 'main'));
$ft->FastPrint();
exit;

?>
