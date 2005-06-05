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
    if (function_exists('array_walk_recursive')) {

        function core_addslashes($k, $v) {
            return addslashes($v);
        }
        array_walk_recursive($_GET, 'core_addslashes');
        array_walk_recursive($_POST, 'core_addslashes');
        array_walk_recursive($_COOKIE, 'core_addslashes');
        @reset($_GET);
        @reset($_POST);
        @reset($_COOKIE);
    } else {

        if(is_array($_GET)) {
            foreach($_GET as $k => $v) {
                if(is_array($_GET[$k])) {
                    foreach ($_GET[$k] as $k2 => $v2) {
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
            foreach ($_POST as $k => $v) {
                if(is_array($_POST[$k])) {
                    foreach ($_POST[$k] as $k2 => $v2) {
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
            foreach ($_COOKIE as $k => $v) {
                if(is_array($_COOKIE[$k])) {
                    foreach( $_COOKIE[$k] as $k2 => $v2) {
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
}

//inicjacja polaczenie z MySQL
$db = new DB_Sql;

//licznik
if(!isset($_COOKIE['devlog_counter'])){
	
	@setcookie('devlog_counter', 'hit', time()+10800);
    set_config('counter', get_config('counter') + 1);
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

$ft->define(array(
    'main_page'         =>'main_page.tpl',
    'note_main'         =>'note_main.tpl',
    'main_denied'       =>'main_denied.tpl',
    'rows'              =>'default_rows.tpl',
    'category_list'     =>'category_list.tpl',
    'newsletter'        =>'newsletter.tpl',
    'query_failed'      =>'query_failed.tpl'
));
    
// warto¶æ poczatkowa zmiennej $start -> potrzebna przy stronnicowaniu
$start  = isset($_GET['start']) ? intval($_GET['start']) : 0;
$val    = empty($val) ? '' : $val;

// generowanie linkow do kanalow rss
if ((bool)$rewrite) {
    $rss_link       = './rss';
    $rssc_link      = './rsscomments';
    $search_link    = 'index.search';
    $cat_all_link   = '1,0,all,item.html';
} else {
    $rss_link       = './rss.php';
    $rssc_link      = './rsscomments.php';
    $search_link    = 'index.php?p=search';
    $cat_all_link   = 'index.php?p=all';
}

$ft->assign(array(
    'TITLE'             =>get_config('title_page'),
    'STATISTICS'        =>get_config('counter'),
    'ENGINE_VERSION'    =>$i18n['index'][1], 
    'RSS_LINK'          =>$rss_link,
    'RSSCOMMENTS_LINK'  =>$rssc_link, 
    'SEARCH_LINK'       =>$search_link,
    'CAT_ALL_LINK'      =>$cat_all_link
));

$max_photo_width = get_config('max_photo_width');

$date_format = get_config('date_format');

if (!isset($_GET['p'])) {

    $start_page_type = get_config('start_page_type');
    switch ($start_page_type) {
        case 'page':
            $p = 5;
            break;

        case 'cat':
            $p = 4;
            break;
            
        case 'all':
            $p = 'all';
            break;

        default:
            $p = '';
    }

    $start_page_id = get_config('start_page_id');
    $id = $start_page_id;
} else {
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $p = $_GET['p'];
}

// G³ówna prze³±cznica includowanej tre¶ci
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

// wyznaczamy szablon jaki ma byc parsowany, sprawdzajac
// czy faktycznie znajduje sie on w katalogu z szablonami
$assigned_tpl = isset($assigned_tpl) && file_exists('./templates/' . $theme . '/tpl/' . $assigned_tpl . '_page.tpl') ? $assigned_tpl : 'main_page';
        
$ft->define_dynamic("alternate_design_row", $assigned_tpl);

while($d = $read_dir->read()) {
    
    if($d[0] != '.') {
        
        // link do alternatywnego szablonu
        $template_link = isset($rewrite) && $rewrite == 1 ? '2,' . $d . ',item.html' : 'design.php?issue=' . $d . '';
        
        $ft->assign(array(
            'ALTERNATE_TEMPLATE'    =>$d,
            'TEMPLATE_LINK'         =>$template_link
        ));
        $ft->parse('ALTERNATE_DESIGN_ROW', ".alternate_design_row");
    }
}

// tablica includowanych modulow
$inc_modules = array(
    'category_list',
    'pages_list',
    'links_list'
);

foreach($inc_modules as $module) {
    
    // ³adowanie dodatkowych modu³ów
    include('modules/' . $module . '.php');
}

$ft->parse('MAIN', $assigned_tpl);
$ft->FastPrint();
exit;

?>
