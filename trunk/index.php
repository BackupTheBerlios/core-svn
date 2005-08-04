<?php
// $Id$

/*
 * IMPORTANT: do not change include to require!
 *
 */
@include_once('administration/inc/config.php');

if(!defined('CORE_INSTALLED')) {
    header('Location: install/install.php');
    exit;
}

require_once(ROOT . 'inc/main_lib.php');
require_once(ROOT . 'inc/common_lib.php');

// mysql_server_version
get_mysql_server_version();

$required_classes = array(
    'db_mysql', 
    'fast_template', 
    'calendar', 
    'view', 
    'tree', 
    'db_config'
);

while(list($c) = each($required_classes)) {
    require_once PATH_TO_CLASSES . '/cls_' . $required_classes[$c] . CLASS_EXTENSION;
}

$view       =& view::instance();
$tree       =& new tree;
$db         =& new DB_Sql;
$db_conf    =& new db_config;

$rewrite            = $db_conf->get_config('mod_rewrite');
$max_photo_width    = $db_conf->get_config('max_photo_width');
$date_format        = $db_conf->get_config('date_format');
$show_calendar      = $db_conf->get_config('show_calendar');
$lang               = $db_conf->get_config('language_set');

require('i18n/' . $lang . '/i18n.php');

// counter
if(!isset($_COOKIE['devlog_counter'])){
	
	@setcookie('devlog_counter', 'hit', time()+10800);
    $db_conf->set_config('counter', $db_conf->get_config('counter') + 1);
}

// template & design switcher
$theme = prepare_template($lang, $i18n);

@setcookie('devlog_design', $theme, time() + 3600 * 24 * 365);

// inicjowanie klasy, wkazanie katalogu przechowuj±cego szablony
$ft = new FastTemplate('./templates/' . $lang . '/' . $theme . '/tpl/');

$templates_dir = 'templates/' . $lang . '/';
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
$start  = isset($_GET['start']) ? (int)$_GET['start'] : 0;
$val    = empty($val) ? '' : $val;

// generowanie linkow
if ((bool)$rewrite) {
    $rss_link       = './rss';
    $rssc_link      = './rsscomments';
    $search_link    = 'index.search';
    $cat_all_link   = '1,0,all,item.html';
} else {
    $rss_link       = './rss.php';
    $rssc_link      = './rsscomments.php';
    $search_link    = 'index.php?p=8';
    $cat_all_link   = 'index.php?p=all';
}

$ft->assign(array(
    'TITLE'             =>$db_conf->get_config('title_page'),
    'STATISTICS'        =>$db_conf->get_config('counter'),
    'ENGINE_VERSION'    =>$i18n['index'][1], 
    'RSS_LINK'          =>$rss_link,
    'RSSCOMMENTS_LINK'  =>$rssc_link, 
    'SEARCH_LINK'       =>$search_link,
    'CAT_ALL_LINK'      =>$cat_all_link,
    'CORE_VERSION'      =>$db_conf->get_config('core_version'), 
    'LANG'              =>$lang, 
    'THEME'             =>$theme
));

if(!isset($_GET['p'])) {

    $start_page_type    = $db_conf->get_config('start_page_type');
    $start_page_id      = $db_conf->get_config('start_page_id');

    switch ($start_page_type) {
        case 'page':    $p = 5;     break;
        case 'cat':     $p = 4;     break;
        case 'all':     $p = 'all'; break;
        default:        $p = '';
    }

    $id = $start_page_id;
} else {
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $p = $_GET['p'];
}

class loader {
    
    var $mod = '';
    var $MODULE_EXTENSION = '.php';
    
    // konstruktor
    function loader() {
        
        global $p;
        
        switch($p){
            
            case '1'    : $this->mod = 'alter_view';        break;
            case '2'    : $this->mod = 'comments_view';     break;
            case '3'    : $this->mod = 'comments_add';      break;
            case '4'    : $this->mod = 'category_view';     break;
            case '5'    : $this->mod = 'pages_view';        break;
            case '6'    : $this->mod = 'articles_view';     break;
            case '7'    : $this->mod = 'newsletter';        break;
            case '8'    : $this->mod = 'search';            break;
            case '9'    : $this->mod = 'date_view';         break;
            
            default     : $this->mod = 'main_view';
        }
        
        $this->mod = $this->name_cleaner($this->mod);
        
        if($this->mod == "") {
            $this->return_dead();
        }

		if(!@file_exists(PATH_TO_MODULES_USER . '/' . $this->mod . $this->MODULE_EXTENSION)) {
			$this->return_dead();
		}
    }
    
	function name_cleaner($name) {
	    
	    return preg_replace("/[^a-zA-Z0-9\-\_]/", "", $name);
	}
	
	function return_dead() {
	    $this->mod = 'main_view';
	}

}

// wyznaczamy szablon jaki ma byc parsowany, sprawdzajac
// czy faktycznie znajduje sie on w katalogu z szablonami
if(!isset($assigned_tpl) || !file_exists('./templates/' . $lang . '/' . $theme . '/tpl/' . $assigned_tpl . '_page.tpl')) {
  $assigned_tpl = 'main_page';
}

$ft->define_dynamic("alternate_design_row", $assigned_tpl);

while($d = $read_dir->read()) {
    if($d[0] != '.') {

        // link do alternatywnego szablonu
        $template_link = (bool)$rewrite ? sprintf('2,%s,item.html', $d) : 'design.php?issue=' . $d;

        $ft->assign(array(
            'ALTERNATE_TEMPLATE'    =>$d,
            'TEMPLATE_LINK'         =>$template_link
        ));
        $ft->parse('ALTERNATE_DESIGN_ROW', ".alternate_design_row");
    }
}

$loader = new loader();
require_once(PATH_TO_MODULES_USER . '/' . $loader->mod . $loader->MODULE_EXTENSION);

// tablica includowanych modulow
$modules = array(
    'category_list',
    'pages_list',
    'links_list'
);

while(list($m) = each($modules)) {
    require_once PATH_TO_MODULES_USER . '/' . $modules[$m] . '.php';
}

if((bool)$show_calendar) {
    $ft->assign('SHOW_CALENDAR', true);
    
    $calendar = new calendar();
    $calendar->display_calendar();
} else {
    $ft->assign('SHOW_CALENDAR', false);
}

$ft->parse('MAIN', $assigned_tpl);
$ft->FastPrint();
exit;

?>