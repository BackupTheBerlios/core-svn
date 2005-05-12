<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {

    case "send":

        include("../inc/main_functions.php");
        define('SQL_SCHEMA', 'dbschema');

        $err    = '';
        $monit  = array(); // tablica przechowuj�ca b��dy

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

                $link   = mysql_pconnect($dbhost, $dbuser, $dbpass) or die('Nie mo�na si� po��czy�: ' . mysql_error());
                $result = mysql_query("CREATE DATABASE $dbname") or die("Nie mo�na utworzy� bazy danych!");
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

            $file .= "define('TABLE_MAIN',        PREFIX . 'devlog');\n";
            $file .= "define('TABLE_USERS',       PREFIX . 'users');\n";
            $file .= "define('TABLE_COMMENTS',    PREFIX . 'comments');\n";
            $file .= "define('TABLE_CONFIG',      PREFIX . 'config');\n";
            $file .= "define('TABLE_CATEGORY',    PREFIX . 'category');\n";
            $file .= "define('TABLE_PAGES',       PREFIX . 'pages');\n";
            $file .= "define('TABLE_LINKS',       PREFIX . 'links');\n";
            $file .= "define('TABLE_NEWSLETTER',  PREFIX . 'newsletter');\n\n";

            $file .= 'define(\'CORE_INSTALLED\', true);'."\n\n";

            $file .= '//mail address to person who can repair if something in Your code is broken' . "\n";
            $file .= 'define(\'ADMIN_MAIL\', \'core@example.com\');'."\n\n";

            $file .= '?' . '>';

            $fp        = @fopen('../administration/inc/config.php', 'w');
            $result = @fputs($fp, $file, strlen($file));
            @fclose($fp);

            $pass    = md5($corepass_1);
            $t1        = $dbprefix . 'users';
            $t2        = $dbprefix . 'category';
            $t3        = $dbprefix . 'config';

            $perms = new permissions();
            // Nadajemu stosowne uprawnienia u�ytkownikowi
            $perms->permissions["user"]                     = TRUE;
            $perms->permissions["writer"]                   = TRUE;
            $perms->permissions["moderator"]                = TRUE;
            $perms->permissions["tpl_editor"]               = TRUE;
            $perms->permissions["admin"]                    = TRUE;

            $bitmask = $perms->toBitmask();

            // wstawiamy pocz�tkowego u�ytkownika
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

            // wstawiamy domy�lnie kategori� og�ln�
            $query = sprintf("
                INSERT INTO
                    %1\$s
                VALUES
                    ('', '', '10', 'og�lna', '')",

                $t2
            );

            $db->query($query);

            // ustawiamy warto�� licznika na 0
            $query = sprintf("
                INSERT INTO
                    %1\$s
                VALUES
                    ('counter', '0')",

                $t3
            );
            
            $db->query($query);
            
            // Ustawiamy ilo�� post�w na stronie w administracji
            $query = sprintf("
                INSERT INTO 
                    %1\$s 
                VALUES 
                    ('editposts_per_page', '15')", 

                $t3
            );

            $db->query($query);
            
            // Ustawiamy mod_rewrite
            $query = sprintf("
                INSERT INTO 
                    %1\$s 
                VALUES 
                    ('mod_rewrite', '0')", 

                $t3
            );

            $db->query($query);

            // Ustawiamy ilo�� post�w na stronie g��wnej
            $query = sprintf("
                INSERT INTO 
                    %1\$s 
                VALUES 
                    ('mainposts_per_page', '4')", 

                $t3
            );

            $db->query($query);

            // Ustawiamy ilo�� post�w najcz�ciej komentowanych wpis�w
            $query = sprintf("
                INSERT INTO 
                    %1\$s 
                VALUES 
                    ('mostcomments_on_page', '20')", 

                $t3
            );

            $db->query($query);

            // Ustawiamy tytu� strony
            $query = sprintf("
                INSERT INTO 
                    %1\$s 
                VALUES 
                    ('title_page', './Core {lektura wcale nie obowi�zkowa}')",

                $t3
            );

            $db->query($query);

            // Ustawiamy maksymaln� szerko�� zdj�cia, jakie
            // jest wyswietlane przy wpisie
            $query = sprintf("
                INSERT INTO 
                    %1\$s 
                VALUES 
                    ('max_photo_width', '440')", 

                $t3 
            );

            $db->query($query);

            // wersja core
            $query = sprintf("
                INSERT INTO 
                    %1\$s 
                VALUES 
                    ('core_version', '0.3.7')", 

                $t3 
            );

            $db->query($query);

            // format daty
            $query = sprintf("
                INSERT INTO 
                    %1\$s 
                VALUES 
                    ('date_format', 'Y-m-d H:i:s')", 

                $t3 
            );

            $db->query($query);

            if($fp == FALSE) {

                $err .= "Instalator nie m�g� stworzy� pliku konfiguracyjnego.<br />";
                $err .= "W katalogu <span class=\"black\">administration/inc/</span> stw�rz plik <span class=\"black\">config.php</span> o tre�ci:<br /><br />";

                $file = str_replace('<', '&lt;', $file);
                $err .= "<div class=\"code\">" . str_nl2br($file) . "</div>";
                $err .= "<br /><br />";
            } else {

                $err .= "Instalacja przebieg�a pomy�lnie.<br />";
                $err .= "Mo�esz przej�� na <a href=\"../\">stron� g��wn�</a>.<br /><br />";
            }

            if(!is_writable('../photos')) {
                $photos_dir = realpath('./../') . '/photos/';

                $err .= "Brak prawa do zapisu w katalogu <span class=\"black\">$photos_dir</span>\n";
                $err .= "Aby umozliwi� wgrywanie zdj��, musisz da� prawo do zapisu do tego";
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
