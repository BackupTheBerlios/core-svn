<?php

$post = !empty($_POST['post']) ? $_POST['post'] : '';
$lang = !empty($_POST['lang']) ? $_POST['lang'] : 'pl';

if(!empty($post)) {

    include("../inc/common_lib.php");
    define('SQL_SCHEMA', 'dbschema');

    $err    = '';
    $monit  = array(); // tablica przechowuj±ca b³êdy

    $dbname = $_POST['dbname'];
    $dbhost = $_POST['dbhost'];
    $dbuser = $_POST['dbuser'];
    $dbpass = $_POST['dbpass'];

    $dbprefix     = $_POST['dbprefix'];

    $coreuser     = $_POST['coreuser'];
    $coremail     = $_POST['coremail'];

    $corepass_1   = $_POST['corepass_1'];
    $corepass_2   = $_POST['corepass_2'];

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

            $link   = mysql_pconnect($dbhost, $dbuser, $dbpass) or die('Nie mo¿na siê po³±czyæ: ' . mysql_error());
            $result = mysql_query("CREATE DATABASE $dbname") or die("Nie mo¿na utworzyæ bazy danych!");
            $link   = mysql_close($link);

        }

        define('DB_HOST', $dbhost);
        define('DB_USER', $dbuser);
        define('DB_PASS', $dbpass);
        define('DB_NAME', $dbname);

        $db = new DB_Sql;
            
        // poprawiono dla wersji php < 4.3.0
        if(!function_exists('file_get_contents')) {
            $sql_query = implode('', file($db_schema));
            $sql_query = explode(';', $sql_query);
        } else {
            $sql_query = explode(';', file_get_contents($db_schema));
        }
            
        $sql_query = str_replace('core_', $dbprefix, $sql_query);

        $sql_size = sizeof($sql_query) - 1;
        for($i = 0; $i < $sql_size; $i++) {

            $db->query($sql_query[$i]);
        }

        $file = '<?php'."\n";
        $file .= "\n// Core CMS auto-generated config file\n\n";
        $file .= 'define(\'DB_HOST\', \'' . $dbhost . '\');' . "\n";
        $file .= 'define(\'DB_USER\', \'' . $dbuser . '\');' . "\n";
        $file .= 'define(\'DB_PASS\', \'' . $dbpass . '\');' . "\n";
        $file .= 'define(\'DB_NAME\', \'' . $dbname . '\');' . "\n";
        $file .= 'define(\'PREFIX\', \'' . $dbprefix . '\');'."\n\n";

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

        $file .= '//mail address to person who can repair if something in Your code is broken' . "\n";
        $file .= "define('ADMIN_MAIL',      'core@example.com');\n\n";

        $file .= '?' . '>';

        $fp     = @fopen('../administration/inc/config.php', 'w');
        $result = @fputs($fp, $file, strlen($file));
        @fclose($fp);

        $pass    = md5($corepass_1);
        $t1        = $dbprefix . 'users';
        $t2        = $dbprefix . 'category';
        $t3        = $dbprefix . 'config';

        $perms = new permissions();
        // Nadajemu stosowne uprawnienia u¿ytkownikowi
        $perms->permissions["user"]                     = TRUE;
        $perms->permissions["writer"]                   = TRUE;
        $perms->permissions["moderator"]                = TRUE;
        $perms->permissions["tpl_editor"]               = TRUE;
        $perms->permissions["admin"]                    = TRUE;

        $bitmask = $perms->toBitmask();

        // wstawiamy pocz±tkowego u¿ytkownika
        $query = sprintf("
            INSERT INTO
                %1\$s
            VALUES
                ('language_set', '%2\$s')",

            $t3,
            $lang
        );

        $db->query($query);
        
        // wstawiamy pocz±tkowego u¿ytkownika
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
        'mysql41'   =>'MySQL 4.1.x'
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