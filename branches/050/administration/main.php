<?php
// $Id: main.php 1128 2005-08-03 22:16:55Z mysz $

/*
 * IMPORTANT: do not change include to require!
 *
 */
@include_once('inc/config.php');
if(!defined('CORE_INSTALLED')) {

    header('Location: ../install/install.php');
    exit;
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
if(!isset($_SESSION['loggedIn']))
{
    header('Location: index.php');
    exit;
}



/*
 * TODO:
 * sprawdzic ktore klasy sa potrzebne _zawsze_, a ktore tylko w niektorych
 * modulach. rozszerzyc tablice $CoreModulesMap, dodajac tam przy okazji
 * te pliki, ktore dodatkowo musza byc ladowane dla danej klasy, a ktore nie
 * sa ladowane domyslnie.
 *
 */
require_once sprintf('%s%sinc%scommon_lib.php', ROOT, DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR);
require_once pathjoin(ROOT, 'inc', 'admin_lib.php');

require_once pathjoin(PATH_TO_CLASSES, 'cls_db_mysql.php');

require_once pathjoin(PATH_TO_CLASSES, 'cls_corebase.php');
require_once pathjoin(PATH_TO_CLASSES, 'cls_news.php');
require_once pathjoin(PATH_TO_CLASSES, 'cls_corenews.php');
require_once pathjoin(PATH_TO_CLASSES, 'cls_upload.php');
require_once pathjoin(PATH_TO_CLASSES, 'cls_rss_parser.php');
require_once pathjoin(PATH_TO_CLASSES, 'cls_links.php');
require_once pathjoin(PATH_TO_CLASSES, 'cls_errors.php');
require_once pathjoin(PATH_TO_CLASSES, 'cls_fast_template.php');
require_once pathjoin(PATH_TO_CLASSES, 'cls_permissions.php');
require_once pathjoin(PATH_TO_CLASSES, 'cls_view.php');
require_once pathjoin(PATH_TO_CLASSES, 'cls_db_config.php');

require_once pathjoin(ROOT, 'administration', 'inc', 'tpl_functions.php');
require_once pathjoin(ROOT, 'inc', 'common_db_lib.php');



// mysql_server_version
get_mysql_server_version();
$lang = get_config('language_set');
require_once pathjoin(ROOT, 'administration', 'i18n', $lang, 'i18n.php');



// warto�� pocz�tkowa zmiennej $start -> potrzebna przy stronnicowaniu
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;

// egzemplarz klasy obs�uguj�cej baz� danych Core
$db = new DB_SQL;


//UPRAWNIENIA
// pobieramy poziom uprawnie�
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

// egzemplarz klasy do obs�ugi uprawnie�
$perms          = new permissions();
$permarr        = $perms->getPermissions($privileges);

switch ($privileges)
{
    case '1':     $privilege_level = 1; break;
    case '3':     $privilege_level = 2; break;
    case '7':     $privilege_level = 3; break;
    case '15':    $privilege_level = 4; break;
    case '31':    $privilege_level = 5; break;             
}







//SZABLONY
// inicjowanie klasy, wkazanie katalogu przechowuj�cego szablony
$ft = new FastTemplate(pathjoin('templates', $lang, 'tpl'));

// tablica definicji u�ytych plik�w *.tpl
$ft->define(array(
        'index'             => 'index.tpl',
        'main_loader'       => 'main_loader.tpl',
        'result_note'       => 'result_note.tpl',
        'menu_header'       => 'menu_header.tpl',
        'menu'              => 'menu.tpl'
));
$ft->define_dynamic('menu_row', 'menu');




// prze��cznica �adowanej tre�ci                    
$CorePage = isset($_GET['p']) ? $_GET['p'] : 0;
$CoreModulesMap = array(
    1  => 'add_note.php',
    2  => 'edit_note.php',
    3  => 'add_page.php',
    4  => 'edit_page.php',
    5  => 'edit_comments.php',
    6  => 'most_comments.php',
    7  => 'add_user.php',
    8  => 'add_category.php',
    9  => 'edit_category.php',
    10 => 'core_configuration.php',
    11 => 'add_links.php',
    12 => 'edit_links.php',
    13 => 'edit_users.php',
    14 => 'edit_templates.php',
    15 => 'transfer_note.php',
    16 => 'list_note.php'
);
if (array_key_exists($CorePage, $CoreModulesMap))
{
    require pathjoin(PATH_TO_MODULES_ADM, $CoreModulesMap[$CorePage]);
}
else
{
    require pathjoin(PATH_TO_MODULES_ADM, 'main.php');
}



//menu glowne - zaznaczenie wybranej zakladki
if (in_array($CorePage, array(1, 2, 5, 6)))
{
    $tag = 'NEWS_CURRENT';
}
elseif (in_array($CorePage, array(3, 4)))
{
    $tag = 'PAGES_CURRENT';
}
elseif (in_array($CorePage, array(7, 13)))
{
    $tag = 'USERS_CURRENT';
}
elseif (in_array($CorePage, array(8, 9, 15)))
{
    $tag = 'CAT_CURRENT';
}
elseif (in_array($CorePage, array(10)))
{
    $tag = 'CONFIG_CURRENT';
}
elseif (in_array($CorePage, array(11, 12)))
{
    $tag = 'LINKS_CURRENT';
}
elseif (in_array($CorePage, array(14)))
{
    $tag = 'TEMPLATES_CURRENT';
}
else
{
    $tag = 'MAIN_CURRENT';
}

//zawartosc submenu
if (in_array($CorePage, array(1, 2, 16, 5, 6)))
{
    $menu_content = array(
        '1'     =>$i18n['subcat_menu'][0], 
        '16'    =>$i18n['subcat_menu'][1], 
        '5'     =>$i18n['subcat_menu'][2], 
        '6'     =>$i18n['subcat_menu'][3]
    );
}
elseif (in_array($CorePage, array(3, 4)))
{
    $menu_content = array(
        '3'     =>$i18n['subcat_menu'][4], 
        '4'     =>$i18n['subcat_menu'][5]
    );
}
elseif (in_array($CorePage, array(7,13)))
{
    $menu_content = array(
        '7'     =>$i18n['subcat_menu'][6], 
        '13'    =>$i18n['subcat_menu'][7]
    );
}
elseif (in_array($CorePage, array(8, 9, 15)))
{
    $menu_content = array(
        '8'     =>$i18n['subcat_menu'][8], 
        '9'     =>$i18n['subcat_menu'][9], 
        '15'    =>$i18n['subcat_menu'][10]
    );
}
elseif (in_array($CorePage, array(10)))
{
    $menu_content = array(
        '10'     =>$i18n['subcat_menu'][11]
    );
}
elseif (in_array($CorePage, array(11, 12)))
{
    $menu_content = array(
        '11'    =>$i18n['subcat_menu'][12], 
        '12'    =>$i18n['subcat_menu'][13]
    );
}
elseif (in_array($CorePage, array(14)))
{
    $menu_content = array(
        '14'     =>$i18n['subcat_menu'][14]
    );
}



if(!empty($CorePage)) {

    // parsujemy menu na podstawie tablicy
    foreach ($menu_content as $menu_num => $menu_desc) {

        $ft->assign(array(
            'MENU_NUMBER'   =>$menu_num, 
            'MENU_DESC'     =>$menu_desc
        ));

        $ft->parse('SUBCAT_MENU', '.menu_row');
    }

    $ft->parse('SUBCAT_MENU', 'menu');
}



$ft->assign(array(
        'PRIVILEGE_LEVEL'   => $privilege_level,
        'PAGE_TITLE'        => $i18n['main'][0],
        'LOGGED_IN'         => $_SESSION['login'],
        'VERSION'           => get_config('core_version'),
        'CSS_HREF'          => sprintf('templates/%s/css/style.css', $lang),
        'LANG'              => $lang,
        $tag                => 'id="current"'
));



$ft->parse('MENU_HEADER', '.menu_header');
$ft->parse('MAIN_CONTENT', array('main_loader', 'index'));

$ft->FastPrint();

?>
