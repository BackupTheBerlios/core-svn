<?php

if(is_file('administration/inc/config.php')) {
    require_once('administration/inc/config.php');
}

if(!defined('CORE_INSTALLED')) {
    header('Location: install/install.php');
    exit;
}

require_once('inc/i18n.php');
require_once('inc/main_functions.php');
define('PATH_TO_CLASSES', get_root() . '/administration/classes');
require_once(PATH_TO_CLASSES. '/cls_db_mysql.php'); // dodawanie pliku konfigurujacego bibliotekê baz danych
require_once(PATH_TO_CLASSES. '/cls_phpmailer.php'); // dodawanie pliku konfigurujacego bibliotekê wysy³ania mail'i
require_once(PATH_TO_CLASSES. '/cls_fast_template.php');

// pobieranie informacji o uzyciu mod_rewrite
$rewrite = get_config('mod_rewrite');

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

//inicjacja polaczenie z MySQL
$db = new DB_Sql;

//licznik
if(!isset($_COOKIE['devlog_counter'])){
	
	@setcookie('devlog_counter', 'hit', time()+10800);
	
    $query = sprintf("
        UPDATE
            %1\$s
		SET
            config_value = '%2\$s'
        WHERE
            config_name = 'counter'",
            
        $mysql_data['db_table_config'],
        get_config('counter') + 1
    );
	$db->query($query);
}

//konfiguracja szablonow i design switchera
if(isset($_COOKIE['devlog_design']) && is_dir('./templates/' . $_COOKIE['devlog_design'] . '/tpl/')){

    $theme = $_COOKIE['devlog_design'];
} elseif (is_dir('./templates/main/tpl')) {

    $theme = 'main';
} else {
    printf('<div style="font-family: Arial, sans-serif; font-size: 16px; background-color: #ccc; border: 1px solid red; padding: 15px; text-align: center;">%s</div>', $i18n['index'][0]);
    exit;
}

@setcookie('devlog_design', $theme, time() + 3600 * 24 * 365);

// inicjowanie klasy, wkazanie katalogu przechowuj±cego szablony
$ft = new FastTemplate('./templates/' . $theme . '/tpl/');

$templates_dir = 'templates/';
$read_dir = @dir($templates_dir);

$ft->define("design_switcher", "design_switcher.tpl");
$ft->define_dynamic("alternate_design_row", "design_switcher");

while($d = $read_dir->read()) {
    
    if($d[0] != '.') {
        
        // link do alternatywnego szablonu
        $template_link = isset($rewrite) && $rewrite == 1 ? '2,' . $d . ',item.html' : 'design.php?issue=' . $d . '';
        
        $ft->assign(array(
            'ALTERNATE_TEMPLATE'    =>$d,
            'TEMPLATE_LINK'         =>$template_link
        ));
        $ft->parse('DESIGN_SWITCHER', ".alternate_design_row");
    }
}
$ft->parse('DESIGN_SWITCHER', 'design_switcher');

$ft->define(array(
    'main'              =>'main.tpl',
    'note_main'         =>'note_main.tpl',
    'rss_view'          =>'rss_view.tpl',
    'main_denied'       =>'main_denied.tpl',
    'comments_main'     =>'comments_main.tpl',
    'comments_alter'    =>'comments_alter.tpl',
    'rows'              =>'rows.tpl',
    'single_rows'       =>'single_rows.tpl',
    'comments_form'     =>'comments_form.tpl',
    'comments_rows'     =>'comments_rows.tpl',
    'comments_submit'   =>'comments_submit.tpl',
    'category_list'     =>'category_list.tpl',
    'newsletter'        =>'newsletter.tpl',
    'query_failed'      =>'query_failed.tpl'
));

    
// warto¶æ pocz¹tkowa zmiennej $start -> potrzebna przy stronnicowaniu
$start  = isset($_GET['start']) ? intval($_GET['start']) : 0;
$val    = empty($val) ? '' : $val;

// generowanie linkow do kanalow rss
$rss_link  = isset($rewrite) && $rewrite == 1 ? './rss' : './rss.php';
$rssc_link = isset($rewrite) && $rewrite == 1 ? './rsscomments' : './rsscomments.php';

$ft->assign(array(
    'TITLE'             =>get_config('title_page'),
    'STATISTICS'        =>get_config('counter'),
    'ENGINE_VERSION'    =>$i18n['index'][1], 
    'RSS_LINK'          =>$rss_link,
    'RSSCOMMENTS_LINK'  =>$rssc_link
));

$max_photo_width = get_config('max_photo_width');

$inc_modules = array(
    'category_list',
    'pages_list',
    'links_list'
);

foreach($inc_modules as $module) {
    
    // ³adowanie dodatkowych modu³ów
    include('modules/' . $module . '.php');
}

// G³ówna prze³±cznica includowanej tre¶ci
$p = empty($_GET['p']) ? '' : $_GET['p'];
switch($p){
            
    case '1': 
        include('modules/alter_view.php');
        break;
        
    case '2': 
        include('modules/comments_view.php');
        break;
        
    case '3':
        include('modules/comments_add.php');
        break;
        
    case '4':
        include('modules/category_view.php');
        break;
        
    case '5':
        include('modules/pages_view.php');
        break;
        
    case '6':
        include('modules/articles_view.php');
        break;        
        
    case 'newsletter':
        include('modules/newsletter.php');
        break;
        
    case 'search':
        include('modules/search.php');
        break;
        
    default:
        include('modules/main_view.php');
        break;
}

$ft->parse('MAIN', array('note_main', 'main'));
$ft->FastPrint();
exit;

?>
