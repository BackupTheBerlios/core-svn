<?php
// $Id: main_content.php 1218 2005-11-06 19:53:42Z lark $

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

$post = !empty($_POST['post']) ? $_POST['post'] : '';
$lang = !empty($_POST['lang']) ? $_POST['lang'] : 'pl';

if(!empty($post)) {

    include("../inc/common_lib.php");
    define('SQL_SCHEMA', 'dbschema');

    $err    = '';
    $monit  = array(); // bugs array

    $dbname = $_POST['dbname'];
    $dbhost = $_POST['dbhost'];
    $dbuser = $_POST['dbuser'];
    $dbpass = $_POST['dbpass'];
    $lang   = $_POST['lang'];

    $dbprefix   = $_POST['dbprefix'];
    
    $corehost   = $_POST['corehost'];

    $coreuser   = $_POST['coreuser'];
    $coremail   = $_POST['coremail'];

    $corepass_1 = $_POST['corepass_1'];
    $corepass_2 = $_POST['corepass_2'];

    if(strlen($coreuser) < 4) {

        $monit[] = $i18n['main_content'][0];
    }

    if(!check_mail($coremail)){

        $monit[] = $i18n['main_content'][1];
    }

    if(strlen($corepass_1) < 6) {

        $monit[] = $i18n['main_content'][2];
    }

    if($corepass_1 != $corepass_2) {

        $monit[] = $i18n['main_content'][3];
    }
    
    if(empty($corehost)) $monit[] = $i18n['main_content'][7];

    $rdbms = empty($_POST['rdbms']) ? '' : $_POST['rdbms'];

    switch ($rdbms) {

        case 'mysql4':
            $db_schema = SQL_SCHEMA . '/core-mysql40_install.sql';
            break;

        case 'mysql41':
            $db_schema = SQL_SCHEMA . '/core-mysql41_install.sql';
            break;
    }

    if(empty($monit)) {

        if(isset($_POST['dbcreate'])) {

            $link   = mysql_pconnect($dbhost, $dbuser, $dbpass) or die('Nie można się połączyć: ' . mysql_error());
            $result = mysql_query("CREATE DATABASE $dbname") or die("Nie można utworzyć bazy danych!");
            $link   = mysql_close($link);

        }

        define('DB_HOST', $dbhost);
        define('DB_USER', $dbuser);
        define('DB_PASS', $dbpass);
        define('DB_NAME', $dbname);

        $db = new DB_Sql;
            
        // fixed for php < 4.3.0
        if(!function_exists('file_get_contents')) {
            $sql_query = implode('', file($db_schema));
            $sql_query = explode(';', $sql_query);
        } else {
            $sql_query = explode(';', file_get_contents($db_schema));
        }
            
        $sql_query = str_replace('core_', $dbprefix, $sql_query);
        $sql_query = $lang == 'en' ? str_replace('DEFAULT_CATEGORY', 'default', $sql_query) : str_replace('DEFAULT_CATEGORY', 'og�lna', $sql_query);

        $sql_size = sizeof($sql_query) - 1;
        for($i = 0; $i < $sql_size; $i++) {

            $db->query($sql_query[$i]);
        }

        $file = "<" . "?php\n";
        $file .= "\n// Core CMS auto-generated config file\n\n";
        $file .= "define('DB_HOST', '" . $dbhost . "');\n";
        $file .= "define('DB_USER', '" . $dbuser . "');\n";
        $file .= "define('DB_PASS', '" . $dbpass . "');\n";
        $file .= "define('DB_NAME', '" . $dbname . "');\n";
        $file .= "define('PREFIX', '" . $dbprefix . "');\n\n";

        $file .= "define('TABLE_ASSIGN2CAT',    PREFIX . 'assign2cat');\n";
        $file .= "define('TABLE_MAIN',          PREFIX . 'devlog');\n";
        $file .= "define('TABLE_USERS',         PREFIX . 'users');\n";
        $file .= "define('TABLE_COMMENTS',      PREFIX . 'comments');\n";
        $file .= "define('TABLE_CONFIG',        PREFIX . 'config');\n";
        $file .= "define('TABLE_CATEGORY',      PREFIX . 'category');\n";
        $file .= "define('TABLE_PAGES',         PREFIX . 'pages');\n";
        $file .= "define('TABLE_LINKS',         PREFIX . 'links');\n";
        $file .= "define('TABLE_NEWSLETTER',    PREFIX . 'newsletter');\n\n";

        $file .= "define('CORE_INSTALLED',  true);\n\n";

        $file .= "//mail address to person who can repair if something in Your code is broken\n";
        $file .= "define('ADMIN_MAIL',      'core@example.com');\n\n";

        $file .= "define('PATH_TO_CLASSES', sprintf('%s/classes/', dirname(dirname(__file__))));\n";
        $file .= "define('ROOT', dirname(dirname(PATH_TO_CLASSES)) . '/'  );\n";
        $file .= "define('PATH_TO_MODULES_ADM', ROOT . 'administration/modules/');\n";
        $file .= "define('PATH_TO_MODULES_USER', ROOT . 'modules/');\n\n";

        $file .= "define('TMPDIR', ROOT . 'administration/_tmp/');\n\n";
        
        $file .= "define('BASE_HREF', '" . $corehost . "');";


        $file .= '?' . '>';

        $fp     = @fopen('../administration/inc/config.php', 'w');
        $result = @fputs($fp, $file, strlen($file));
        @fclose($fp);

        $pass   = md5($corepass_1);
        $t1     = $dbprefix . 'users';
        $t2     = $dbprefix . 'category';
        $t3     = $dbprefix . 'config';

        $perms = new permissions();
        
        // set permissions to default user
        $perms->permissions["user"]                     = TRUE;
        $perms->permissions["writer"]                   = TRUE;
        $perms->permissions["moderator"]                = TRUE;
        $perms->permissions["tpl_editor"]               = TRUE;
        $perms->permissions["admin"]                    = TRUE;

        $bitmask = $perms->toBitmask();

        // default langauge
        $query = sprintf("
            INSERT INTO
                %1\$s
            VALUES
                ('language_set', '%2\$s')",

            $t3,
            $lang
        );

        $db->query($query);
        
        // set default user
        $query = sprintf("
            INSERT INTO
                %1\$s
            VALUES
                ('', '%2\$s', '%3\$s', '%4\$s', '%5\$d', 'Y', '', '', '', '', '', '', '', '', '', '')",

            $t1,
            $coreuser,
            $pass,
            $coremail,
            $bitmask
        );

        $db->query($query);

        if($fp == FALSE) {

            $err .= $i18n['main_content'][5];

            $file = str_replace('<', '&lt;', $file);
            $err .= "<div class=\"code\">" . str_nl2br($file) . "</div>";
            $err .= "<br /><br />";
        } else {

            $err .= $i18n['main_content'][4];
        }

        if(!is_writable('../photos')) {
            $photos_dir = realpath('./../') . '/photos/';

            $err .= $i18n['main_content'][6];
        }

        $ft->assign('MONIT', $err);
        $ft->define('monit_content', "monit_content.tpl");

        $ft->parse('ROWS', ".monit_content");
    } else {

        $ft->define("error_reporting", "error_reporting.tpl");
        $ft->define_dynamic("error_row", "error_reporting");

        foreach ($monit as $error) {
    
            $ft->assign('ERROR_MONIT', $error);
                    
            $ft->parse('ROWS',	".error_row");
        }
                        
        $ft->parse('ROWS', "error_reporting");
    }
} else {
    
    $ft->define('main_content', 'main_content.tpl');
    $ft->define_dynamic('lang_row', 'main_content');
    $ft->define_dynamic('db_row', 'main_content');
        
    $databases = array(
        'mysql4'    =>'MySQL 4.0.x', 
        'mysql41'   =>'MySQL 4.1.x', 
        'pgsql7'    =>'PostgreSQL 7.x'
    );
        
    foreach($databases as $key => $row) {
            
        $ft->assign(array(
            'DATABASE_VALUE'    =>$key, 
            'DATABASE_NAME'     =>$row
        ));
        $ft->parse('DB_ROW', '.db_row');
    }
        
    $templates_dir = 'templates/';
    $read_dir = @dir($templates_dir);
        
    while($d = $read_dir->read()) {
        if($d[0] != '.') {
                
            $ft->assign(array(
                'SELECTED_LANG' =>$d, 
                'CURRENT'       =>$lang == $d ? 'selected="selected"' : ''
            ));
            $ft->parse('LANG_ROW', '.lang_row');
        }
    }

    $ft->assign(array(
        'HOST'      =>'localhost',
        'PREFIX'    =>'core_'
    ));
        
    $ft->parse('ROWS', "main_content");

}

?>