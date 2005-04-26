<?php

session_register("login");
session_register("loggedIn");

if(!isset($_SESSION["loggedIn"])){
	
	header("Location: index.php");
	exit;
}

// warto¶æ pocz±tkowa zmiennej $start -> potrzebna przy stronnicowaniu
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;

define('PATH_TO_CLASSES', 'classes');

require(PATH_TO_CLASSES. '/cls_db_mysql.php');
require(PATH_TO_CLASSES. '/cls_upload.php');

require_once("inc/config.php");
include_once("../inc/main_functions.php");
require_once('../inc/i18n_administration.php');

require(PATH_TO_CLASSES. '/cls_fast_template.php');
require(PATH_TO_CLASSES. '/cls_permissions.php');

// automatyczne sprawdzanie stanu magic_quotes
// i w zaleznosci od tego wstawianie addslashes, badz nie.
if(!get_magic_quotes_gpc()) {
    if(is_array($_GET)) {
        while(list($k, $v) = each($_GET)) {
            if(is_array($_GET[$k])) {
                while(list($k2, $v2) = each($_GET[$k])) {
                    $_GET[$k][$k2] = addslashes($v2);
                }
                @reset($_GET[$k]);
            } else {
                $_GET[$k] = addslashes($v);
            }
        }
        @reset($_GET);
    }
    
    if(is_array($_POST)) {
        while(list($k, $v) = each($_POST)) {
            if(is_array($_POST[$k])) {
                while(list($k2, $v2) = each($_POST[$k])) {
					$_POST[$k][$k2] = addslashes($v2);
                }
                @reset($_POST[$k]);
            } else {
                $_POST[$k] = addslashes($v);
            }
        }
        @reset($_POST);
    }
    
    if(is_array($_COOKIE)) {
        while(list($k, $v) = each($_COOKIE)) {
            if(is_array($_COOKIE[$k])) {
                while(list($k2, $v2) = each($_COOKIE[$k])) {
                    $_COOKIE[$k][$k2] = addslashes($v2);
                }
                @reset($_COOKIE[$k]);
            } else {
				$_COOKIE[$k] = addslashes($v);
            }
        }
        @reset($_COOKIE);
    }
}

// egzemplarz klasy obs³uguj±cej bazê danych Core
$db = new DB_SQL;

// pobieramy poziom uprawnieñ
$query = sprintf("
    SELECT 
        permission_level 
    FROM 
        %1\$s 
    WHERE 
        login = '%2\$s'",
    
    $mysql_data['db_table_users'], 
    $_SESSION['login']
);
    
$db->query($query);
$db->next_record();

$privileges = $db->f('permission_level');

// egzemplarz klasy do obs³ugi uprawnieñ
$perms      = new permissions();
$permarr    = $perms->getPermissions($privileges);

switch ($privileges) {
    
    case '1':
    
        $privilege_level = 1;
        break;
        
    case '3':
    
        $privilege_level = 2;
        break;

    case '7':
    
        $privilege_level = 3;
        break;
        
    case '15':
    
        $privilege_level = 4;
        break;
        
    case '31':
    
        $privilege_level = 5;
        break;       
}

// inicjowanie klasy, wkazanie katalogu przechowuj±cego szablony
$ft = new FastTemplate("./templates/tpl");

// tablica definicji u¿ytych plików *.tpl
$ft->define(array(
    'index'         =>"index.tpl",
    'main_loader'   =>"main_loader.tpl",
    'main_site'     =>"main_site.tpl",
    
    // szablon obs³uguj±cy error handlera, jak i dodatkowe komunikaty
    'result_note'   =>"result_note.tpl"
));
		
$ft->assign(array(
    'PRIVILEGE_LEVEL'   =>$privilege_level,
    'PAGE_TITLE'        =>$i18n['main'][0],
    'LOGGED_IN'         =>$_SESSION['login'],
    'VERSION'           =>get_config('core_version')
));

$inc_modules = array(
    "header_menu",
    "subcat_menu"
);

foreach($inc_modules as $module) {
    
    // ³adowanie dodatkowych modu³ów
    include('modules/' . $module . '.php');
}

// prze³±cznica ³adowanej tre¶ci					
$p = empty($_GET['p']) ? '' : $_GET['p'];
switch($p){
	
	// dodawanie kolejnego wpisu
	case '1': 
		include('modules/add_note.php');
		break;

	// edycja/usuwanie istniej±cych wpisów
	case '2': 
		include('modules/edit_note.php');
		break;
		
	// dodawanie kolejnej strony
	case '3': 
		include('modules/add_page.php');
		break;

	// edycja/usuwanie istniej±cych wpisów
	case '4': 
		include('modules/edit_page.php');
		break;
		
	// edycja/usuwanie istniej±cych komentarzy
	case '5': 
		include('modules/edit_comments.php');
		break;
		
	// statystycznie najczê¶ciej komentowane wpisy
	case '6': 
		include('modules/most_comments.php');
		break;	
		
	// dodanie nowego u¿ytkownika systemu	
	case '7': 
		include('modules/add_user.php');
		break;
		
	// dodanie nowej kategorii	
	case '8': 
		include('modules/add_category.php');
		break;
		
	// edycja|usuwanie istniej±cych kategorii	
	case '9': 
		include('modules/edit_category.php');
		break;
		
	// konfiguracja core	
	case '10': 
		include('modules/core_configuration.php');
		break;
		
	// dodanie nowego linku	
	case '11': 
		include('modules/add_links.php');
		break;
		
	// edycja|usuwanie istniej±cych linków	
	case '12': 
		include('modules/edit_links.php');
		break;
		
	// edycja|usuwanie u¿ytkowników	
	case '13': 
		include('modules/edit_users.php');
		break;
		
	// edycja|usuwanie szablonów	
	case '14': 
		include('modules/edit_templates.php');
		break;
		
	// transfer wpisów miêdzy kategoriami	
	case '15': 
		include('modules/transfer_note.php');
		break;
	
	// domy¶lnie	
	default:
		include('modules/main.php');
		break;
}

$ft->parse('MAIN_CONTENT', array("main_loader", "index"));

$ft->FastPrint();
exit;

?>
