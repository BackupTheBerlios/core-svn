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
define('PATH_TO_MODULES', 'modules');

require(PATH_TO_CLASSES. '/cls_db_mysql.php');
require(PATH_TO_CLASSES. '/cls_upload.php');
require(PATH_TO_CLASSES. '/cls_rss_parser.php');

require_once('inc/config.php');
require_once('../inc/common_lib.php');
require_once('../inc/admin_lib.php');

// mysql_server_version
get_mysql_server_version();

$lang = get_config('language_set');

require_once('i18n/' . $lang . '/i18n.php');
require_once(PATH_TO_CLASSES. '/cls_fast_template.php');
require_once(PATH_TO_CLASSES. '/cls_permissions.php');

// inicjowanie klasy, wkazanie katalogu przechowuj±cego szablony
$ft = new FastTemplate('./templates/' . $lang . '/tpl');


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
    
    TABLE_USERS, 
    $_SESSION['login']
);
    
$db->query($query);
$db->next_record();

$privileges = $db->f('permission_level');

// egzemplarz klasy do obs³ugi uprawnieñ
$perms      = new permissions();
$permarr    = $perms->getPermissions($privileges);

$ft->assign(array(
  'PERMS_USER'      => false,
  'PERMS_WRITER'    => false,
  'PERMS_MODERATOR' => false,
  'PERMS_TPLEDITOR' => false,
  'PERMS_ADMIN'     => false,
));
switch ($privileges) {
    
    case '1':
      $privilege_level = 1;
      $ft->assign('PERMS_USER', true);
      break;
    case '3':
      $privilege_level = 2;
      $ft->assign('PERMS_USER', true);
      $ft->assign('PERMS_WRITER', true);
      break;
    case '7':
      $privilege_level = 3;
      $ft->assign('PERMS_USER', true);
      $ft->assign('PERMS_WRITER', true);
      $ft->assign('PERMS_MODERATOR', true);
      break;
    case '15':
      $privilege_level = 4;
      $ft->assign('PERMS_USER', true);
      $ft->assign('PERMS_WRITER', true);
      $ft->assign('PERMS_MODERATOR', true);
      $ft->assign('PERMS_TPLEDITOR', true);
      break;
    case '31':
      $privilege_level = 5;
      $ft->assign('PERMS_USER', true);
      $ft->assign('PERMS_WRITER', true);
      $ft->assign('PERMS_MODERATOR', true);
      $ft->assign('PERMS_TPLEDITOR', true);
      $ft->assign('PERMS_ADMIN', true);
      break;       
}

// tablica definicji u¿ytych plików *.tpl
$ft->define(array(
    'index'         =>'index.tpl',
    'main_loader'   =>'main_loader.tpl',
    'result_note'   =>'result_note.tpl'
));
		
$ft->assign(array(
    'PRIVILEGE_LEVEL'   =>$privilege_level,
    'PAGE_TITLE'        =>$i18n['main'][0],
    'LOGGED_IN'         =>$_SESSION['login'],
    'VERSION'           =>get_config('core_version'), 
    'CSS_HREF'          =>'templates/' . $lang . '/css/style.css', 
    'LANG'              =>$lang
));

// prze³±cznica ³adowanej tre¶ci					
$p = empty($_GET['p']) ? '' : $_GET['p'];


$inc_modules = array(
    'header_menu',
    'subcat_menu'
);

foreach($inc_modules as $module) {
    
    // ³adowanie dodatkowych modu³ów
    include('modules/' . $module . '.php');
}

class loader {
    
    var $mod = '';
    var $MODULE_EXTENSION = '.php';
    
    // konstruktor
    function loader() {
        
        global $p;
        
        switch($p){
            
            case '1'    : $this->mod = 'add_note';              break;
            case '2'    : $this->mod = 'edit_note';             break;
            case '3'    : $this->mod = 'add_page';              break;
            case '4'    : $this->mod = 'edit_page';             break;
            case '5'    : $this->mod = 'edit_comments';         break;
            case '6'    : $this->mod = 'most_comments';         break;
            case '7'    : $this->mod = 'add_user';              break;
            case '8'    : $this->mod = 'add_category';          break;
            case '9'    : $this->mod = 'edit_category';         break;
            case '10'   : $this->mod = 'core_configuration';    break;
            case '11'   : $this->mod = 'add_links';             break;
            case '12'   : $this->mod = 'edit_links';            break;
            case '13'   : $this->mod = 'edit_users';            break;
            case '14'   : $this->mod = 'edit_templates';        break;
            case '15'   : $this->mod = 'transfer_note';         break;
            
            default     : $this->mod = 'main';
        }
        
        $this->mod = $this->name_cleaner($this->mod);
        
        if($this->mod == '') {
            $this->return_dead();
        }

		if(!@file_exists(PATH_TO_MODULES . '/' . $this->mod . $this->MODULE_EXTENSION)) {
			$this->return_dead();
		}
    }
    
	function name_cleaner($name) {
	    
	    return preg_replace('/[^a-zA-Z0-9\-\_]/', '', $name);
	}
	
	function return_dead() {
	    $this->mod = 'main';
	}

}

$loader = new loader();
require_once(PATH_TO_MODULES . '/' . $loader->mod . $loader->MODULE_EXTENSION);

$ft->parse('MAIN_CONTENT', array('main_loader', 'index'));

$ft->FastPrint();
exit;

?>
