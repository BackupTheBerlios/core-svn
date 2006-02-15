<?php
// $Id$

/*
 * This file is internal part of Core CMS (http://core-cms.com/) engine.
 *
 * Copyright (C) 2004-2005 Core Dev Team (more info: docs/AUTHORS).
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published
 * by the Free Software Foundation; version 2 only.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 */

header('Content-type: text/html; charset=UTF8');

/*
 * IMPORTANT: do not change include to require!
 *
 */
@include_once('administration/inc/config.php');

if(!defined('CORE_INSTALLED')) {
    header('Location: install/install.php');
    exit;
}

require_once 'inc/common_lib.php';
require_once pathjoin(ROOT, 'inc', 'main_lib.php');



// mysql_server_version
get_mysql_server_version();

$required_classes = array(
    'db_mysql.php', 
    'fast_template.php', 
    'calendar.php', 
    'view.php', 
    'tree.php', 
    'db_config.php',
    'corebase.php',
    'news.php',
    'corenews.php', 
    'corerewrite.php'
);

while(list($c) = each($required_classes)) {
    require_once pathjoin(PATH_TO_CLASSES, 'cls_' . $required_classes[$c]);
}

$view       =& view::instance();
$tree       =& new tree;
$db         =& new DB_Sql;
$db_conf    =& new db_config;

$CoreRewrite    = new CoreRewrite();

$rewrite            = $db_conf->get_config('mod_rewrite');
$max_photo_width    = $db_conf->get_config('max_photo_width');
$date_format        = $db_conf->get_config('date_format');
$show_calendar      = $db_conf->get_config('show_calendar');
$lang               = $db_conf->get_config('language_set');

require_once pathjoin(ROOT, 'i18n', $lang, 'i18n.php');

// counter
if(!isset($_COOKIE['core_counter'])){
	
	@setcookie('core_counter', 'hit', time()+10800);
    $db_conf->set_config('counter', $db_conf->get_config('counter') + 1);
}

// template & design switcher
$theme = prepare_template($lang, $i18n);

@setcookie('devlog_design', $theme, time() + 3600 * 24 * 365);

// inicjowanie klasy, wkazanie katalogu przechowuj�cego szablony
$templates_dir = pathjoin(ROOT, 'templates', $lang);
$ft = new FastTemplate(pathjoin($templates_dir, $theme, 'tpl'));

$read_dir = @dir($templates_dir);

$ft->define(array(
    'main_page'         => 'main_page.tpl',
    'note_main'         => 'note_main.tpl',
    'main_denied'       => 'main_denied.tpl',
    'rows'              => 'default_rows.tpl',
    'category_list'     => 'category_list.tpl',
    'newsletter'        => 'newsletter.tpl',
    'query_failed'      => 'query_failed.tpl'
));
    

// warto�� poczatkowa zmiennej $start -> potrzebna przy stronnicowaniu
$start  = isset($_GET['start']) ? (int)$_GET['start'] : 0;
$val    = empty($val) ? '' : $val;

// generowanie linkow
if ((bool)$rewrite) {
    $rss_link       = './rss';
    $rssc_link      = './rsscomments';
} else {
    $rss_link       = './rss.php';
    $rssc_link      = './rsscomments.php';
}

$ft->assign(array(
    'TITLE'             =>$db_conf->get_config('title_page'),
    'STATISTICS'        =>$db_conf->get_config('counter'),
    'RSS_LINK'          =>$rss_link,
    'RSSCOMMENTS_LINK'  =>$rssc_link, 
    'SEARCH_LINK'       =>$CoreRewrite->search($rewrite),
    'CAT_ALL_LINK'      =>$CoreRewrite->category_all($rewrite),
    'CORE_VERSION'      =>$db_conf->get_config('core_version'), 
    'LANG'              =>$lang, 
    'THEME'             =>$theme, 
    'BASE_HREF'         =>BASE_HREF
));

if(!isset($_GET['p'])) {

    $start_page_type    = $db_conf->get_config('start_page_type');
    $start_page_id      = $db_conf->get_config('start_page_id');

    switch ($start_page_type) {
        case 'page':    $CorePage = 5;     break;
        case 'cat':     $CorePage = 4;     break;
        case 'all':     $CorePage = 'all'; break;
        default:        $CorePage = '';
    }

    $CoreId = $start_page_id;
} else {

    $CoreId     = isset($_GET['id']) ? $_GET['id'] : '';
    $CorePage   = $_GET['p'];
}



$CoreModulesMap = array(
    1 => 'alter_view.php',
    2 => 'comments_view.php',
    3 => 'comments_add.php',
    4 => 'category_view.php',
    5 => 'pages_view.php',
    6 => 'articles_view.php',
    7 => 'newsletter.php',
    8 => 'search.php',
    9 => 'date_view.php'
);

require_once pathjoin(
    PATH_TO_MODULES_USER, 
        array_key_exists($CorePage, $CoreModulesMap) ? $CoreModulesMap[$CorePage] : 'main_view.php');
    
// wyznaczamy szablon jaki ma byc parsowany, sprawdzajac
// czy faktycznie znajduje sie on w katalogu z szablonami
if(!isset($assigned_tpl) || !file_exists(pathjoin(ROOT, 'templates', $lang, $theme, 'tpl', $assigned_tpl . '_page.tpl'))) {
    $assigned_tpl = 'main_page';
}

$ft->define_dynamic('alternate_design_row', $assigned_tpl);

while($d = $read_dir->read()) {
    if($d[0] != '.') {

        $ft->assign(array(
            'ALTERNATE_TEMPLATE'    =>$d,
            'TEMPLATE_LINK'         =>$CoreRewrite->template_switch($d, $rewrite)
        ));
        $ft->parse('ALTERNATE_DESIGN_ROW', '.alternate_design_row');
    }
}

// tablica includowanych modulow
$modules = array(
    'category_list',
    'pages_list',
    'links_list'
);

while(list($m) = each($modules)) {
    require_once PATH_TO_MODULES_USER . $modules[$m] . '.php';
}

if((bool)$show_calendar) {
    $ft->assign(array(
        'LINKED'        =>false, 
        'SHOW_CALENDAR' =>true
    ));
    
    $calendar = new calendar();
    $calendar->display_calendar();
} else {
    $ft->assign(array(
        'LINKED'        =>false, 
        'SHOW_CALENDAR' =>false
    ));
}

$ft->parse('MAIN', $assigned_tpl);
$ft->FastPrint();
exit;

?>
