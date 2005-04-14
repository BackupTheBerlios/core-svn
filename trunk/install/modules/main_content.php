<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {

    case "send":

        include("../inc/main_functions.php");
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
                $db_schema = SQL_SCHEMA . '/core-mysql40.sql';
                break;

            case 'mysql41':
                $db_schema = SQL_SCHEMA . '/core-mysql41.sql';
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

            $sql_query = explode(';', file_get_contents($db_schema));
            $sql_query = str_replace('core_', $dbprefix, $sql_query);

            $sql_size = sizeof($sql_query) - 1;
            for ($i = 0; $i < $sql_size; $i++) {

                $db->query($sql_query[$i]);
            }

            $file = '<?php'."\n";
            $file .= "\n// Core - plik konfiguracyjny wygenerowany automatycznie\n\n";
            $file .= 'define(\'DB_HOST\', \'' . $dbhost . '\');' . "\n";
            $file .= 'define(\'DB_USER\', \'' . $dbuser . '\');' . "\n";
            $file .= 'define(\'DB_PASS\', \'' . $dbpass . '\');' . "\n";
            $file .= 'define(\'DB_NAME\', \'' . $dbname . '\');' . "\n";
            $file .= 'define(\'PREFIX\', \'' . $dbprefix . '\');'."\n\n";

            $file .= '$mysql_data = array(' . "\n";
            $file .= "\t" . "'db_table'             =>PREFIX . 'devlog',\n";
            $file .= "\t" . "'db_table_users'       =>PREFIX . 'users',\n";
            $file .= "\t" . "'db_table_comments'    =>PREFIX . 'comments',\n";
            $file .= "\t" . "'db_table_config'      =>PREFIX . 'config',\n";
            $file .= "\t" . "'db_table_counter'     =>PREFIX . 'counter',\n";
            $file .= "\t" . "'db_table_category'    =>PREFIX . 'category',\n";
            $file .= "\t" . "'db_table_pages'       =>PREFIX . 'pages',\n";
            $file .= "\t" . "'db_table_links'       =>PREFIX . 'links',\n";
            $file .= "\t" . "'db_table_newsletter'  =>PREFIX . 'newsletter',\n";
            $file .= ");\n\n";

            $file .= 'define(\'CORE_INSTALLED\', true);'."\n\n";

            $file .= '//mail address to person who can repair if something in Your code is broken' . "\n";
            $file .= 'define(\'ADMIN_MAIL\', \'core@example.com\');'."\n\n";

            $file .= '$days_to = 360;' . "\n\n";
            $file .= '?' . '>';

            $fp        = @fopen('../administration/inc/config.php', 'w');
            $result = @fputs($fp, $file, strlen($file));
            @fclose($fp);

            $pass    = md5($corepass_1);
            $t1        = $dbprefix . 'users';
            $t2        = $dbprefix . 'category';
            $t3        = $dbprefix . 'config';

            $perms = new permissions();
            // Nadajemu stosowne uprawnienia u¿ytkownikowi
            $perms->permissions["read"]                  = TRUE;
            $perms->permissions["write"]                 = TRUE;
            $perms->permissions["delete"]                = TRUE;
            $perms->permissions["change_permissions"]    = TRUE;
            $perms->permissions["admin"]                 = TRUE;

            $bitmask = $perms->toBitmask();

            // wstawiamy pocz±tkowego u¿ytkownika
            $query = sprintf("
                INSERT INTO
                    %1\$s
                VALUES
                    ('', '%2\$s', '%3\$s', '%4\$s', '%5\$d', 'Y')",

                $t1,
                $coreuser,
                $pass,
                $coremail,
                $bitmask
            );

            $db->query($query);

            // wstawiamy domy¶lnie kategoriê ogóln±
            $query = sprintf("
                INSERT INTO
                    %1\$s
                VALUES
                    ('', 'ogólna', '')",

                $t2
            );

            $db->query($query);

            // ustawiamy warto¶æ licznika na 0
            $query = sprintf("
                INSERT INTO
                    %1\$s
                VALUES
                    ('counter', '0')",

                $t3
            );
            
            $db->query($query);
            
            // Ustawiamy ilo¶æ postów na stronie w administracji
            $query = sprintf("
                INSERT INTO 
                    %1\$s 
                VALUES 
                    ('editposts_per_page', '5')", 

                $t3
            );

            $db->query($query);

            // Ustawiamy ilo¶æ postów na stronie g³ównej
            $query = sprintf("
                INSERT INTO 
                    %1\$s 
                VALUES 
                    ('mainposts_per_page', '4')", 

                $t3
            );

            $db->query($query);

            // Ustawiamy ilo¶æ postów najczê¶ciej komentowanych wpisów
            $query = sprintf("
                INSERT INTO 
                    %1\$s 
                VALUES 
                    ('mostcomments_on_page', '20')", 

                $t3
            );

            $db->query($query);

            // Ustawiamy tytu³ strony
            $query = sprintf("
                INSERT INTO 
                    %1\$s 
                VALUES 
                    ('title_page', './Core {lektura wcale nie obowi±zkowa}')",

                $t3
            );

            $db->query($query);

            // Ustawiamy maksymaln± szerko¶æ zdjêcia, jakie
            // jest wyswietlane przy wpisie
            $query = sprintf("
                INSERT INTO 
                    %1\$s 
                VALUES 
                    ('max_photo_width', '440')", 

                $t3 
            );

            $db->query($query);

            if($fp == FALSE) {

                $err .= "Instalator nie móg³ stworzyæ pliku konfiguracyjnego.<br />";
                $err .= "W katalogu <span class=\"black\">administration/inc/</span> stwórz plik <span class=\"black\">config.php</span> o tre¶ci:<br /><br />";

                $file = str_replace('<', '&lt;', $file);
                $err .= "<div class=\"code\">" . str_nl2br($file) . "</div>";
                $err .= "<br /><br />";
            } else {

                $err .= "Instalacja przebieg³a pomy¶lnie.<br />";
                $err .= "Mo¿esz przej¶æ na <a href=\"../\">stronê g³ówn±</a>.<br /><br />";
            }

            if(!is_writable('../photos')) {
                $photos_dir = realpath('./../') . '/photos/';

                $err .= "Brak prawa do zapisu w katalogu <span class=\"black\">$photos_dir</span>\n";
                $err .= "Aby umozliwiæ wgrywanie zdjêæ, musisz daæ prawo do zapisu do tego";
                $err .= " katalogu (np. zaloguj sie na konto, i wydaj komende:\n";
                $err .= " <div class=\"code\">chmod 777 $photos_dir</div>";
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
        break;

    default:

        $ft->assign(array(
            'HOST'      =>'localhost',
            'PREFIX'    =>'core_'
        ));
        $ft->define('main_content', "main_content.tpl");
        $ft->parse('ROWS', ".main_content");
        break;
}
?>
