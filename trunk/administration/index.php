<?php
session_register("login");
session_register("loggedIn");

if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === TRUE){
    
    header("Location: main.php");
    break;
}

define('PATH_TO_CLASSES', 'classes');

require_once(PATH_TO_CLASSES. '/cls_db_mysql.php');
require_once(PATH_TO_CLASSES. '/cls_phpmailer.php');

require_once("inc/config.php");
require_once('../inc/common_lib.php');

// mysql_server_version
get_mysql_server_version();

$lang = get_config('language_set');

require_once('i18n/' . $lang . '/i18n.php');
require_once(PATH_TO_CLASSES. '/cls_fast_template.php');

// warto¶æ pocz±tkowa zmiennej $start -> potrzebna przy stronnicowaniu
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;

// inicjowanie klasy, wkazanie katalogu przechowuj±cego szablony
$ft = new FastTemplate('./templates/' . $lang . '/tpl');

$ft->define(array(
    'main'              =>"main.tpl",
    'main_loader'       =>"main_loader.tpl",
    'rows'              =>"rows.tpl",
    'form_login'        =>"form_login.tpl"
));
        
$ft->assign(array(
    'TITLE'         =>$i18n['index'][0],
    'ERROR_MSG'     =>'', 
    'CSS_HREF'      =>'templates/' . $lang . '/css/style.css'
));

// deklaracja zmiennej $p
$p = empty($_GET['p']) ? '' : $_GET['p'];

if($p == "log") {
    
    $login       = trim($_POST['login']);
    $password    = trim(md5($_POST['password']));
    
    if(empty($login) OR empty($password)) {
        
        // U¿ytkownik nie uzupe³ni³ wszystkich pól::form
        $ft->assign('ERROR_MSG', $i18n['index'][1]);
        $ft->parse('ROWS', ".form_login");
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

            if($db->f("active") != "N") {
                
                // Rejestrujemy zmienne sesyjne
                $_SESSION["login"]       = $login;
                $_SESSION["loggedIn"]    = TRUE;
        
                header("Location: main.php");
                break;
            } else {
                
                // U¿ytkownik nie zaaktywowa³ konta::db
                $ft->assign('ERROR_MSG', $i18n['index'][2]);
                $ft->parse('ROWS', ".form_login");
            }
        } else {
            // Niepoprawne dane wej¶cia<->wyj¶cia::form, db
            $ft->assign('ERROR_MSG', $i18n['index'][3]);
            $ft->parse('ROWS', ".form_login");
        }
    }
} else {
    include("modules/login.php");
    
}

$ft->parse('MAIN', array("main_loader", "main"));
$ft->FastPrint();
exit;

?>