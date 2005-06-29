<?php

class install {
        
    public $err     = '';
    public $monit   = array(); // errors array
    public $lang;
        
    protected $dbname;
    protected $dbhost;
    protected $dbuser;
    protected $dbpass;
    protected $dbcreate;
    protected $dbprefix;
        
    protected $coreuser;
    protected $coremail;
        
    protected $corepass_1;
    protected $corepass_2;
        
    public $db_schema;
        
        
    /**
     * Constructor
     * Initialize variables
     */
    function __construct() {
            
        $this->dbname   = $_POST['dbname'];
        $this->dbhost   = $_POST['dbhost'];
        $this->dbuser   = $_POST['dbuser'];
        $this->dbpass   = $_POST['dbpass'];
            
        $this->dbprefix = $_POST['dbprefix'];
            
        $this->lang     = $_POST['lang'];
            
        $this->coreuser = $_POST['coreuser'];
        $this->coremail = $_POST['coremail'];
            
        $this->corepass_1   = $_POST['corepass_1'];
        $this->corepass_2   = $_POST['corepass_2'];
            
        // Form data validation
        $this->valid_data();
    }
        
        
    /**
     * Form data need to be valid
     * @return $monit
     */
    function valid_data() {
            
        global $i18n;
            
        if(strlen($this->coreuser) < 4) {
            $this->monit[] = $i18n['main_content'][0];
        }
            
        if(!check_mail($this->coremail)) {
            $this->monit[] = $i18n['main_content'][1];
        }
            
        if(strlen($this->corepass_1) < 6) {
            $this->monit[] = $i18n['main_content'][2];
        }
            
        if($this->corepass_1 != $this->corepass_2) {
            $this->monit[] = $i18n['main_content'][3];
        }
            
        if(empty($this->monit)) {
            $this->do_install();
        } else {
            $this->failed_install();
        }
    }
        
        
    function do_install() {
            
        global $ft, $i18n;
            
        $this->dbcreate     = $_POST['dbcreate'];
        $this->db_schema    = SQL_SCHEMA . 'core-mysql_install.sql';
            
        if(isset($this->dbcreate)) {
            $dsn = 'mysql:host=' . $this->dbhost;
                
            try {
                $dbh = new PDO($dsn, $this->dbuser, $this->dbpass);
            } catch (PDOException $e) {
                echo 'Wyjatek z³apany: ' . $e->getMessage();
            }
                
            $dbh->exec("CREATE DATABASE $this->dbname");
        }
            
        $dsn = 'mysql:dbname=' . $this->dbname . ';host=' . $this->dbhost;
            
        try {
            $dbh = new PDO($dsn, $this->dbuser, $this->dbpass);
        } catch (PDOException $e) {
            echo 'Wyjatek z³apany: ' . $e->getMessage();
        }
            
        $sql_query = explode(';', file_get_contents($this->db_schema));
        $sql_query = str_replace('core_', $this->dbprefix, $sql_query);
        $sql_query = $this->lang == 'en' ? str_replace('DEFAULT_CATEGORY', 'default', $sql_query) : str_replace('DEFAULT_CATEGORY', 'ogólna', $sql_query);
            
        $sql_size = sizeof($sql_query) - 1;
        for($i = 0; $i < $sql_size; $i++) {
            $dbh->exec($sql_query[$i]);
        }

        $file = '<?php'."\n";
        $file .= "\n// Core CMS auto-generated config file\n\n";
        $file .= 'define(\'DB_HOST\', \'' . $this->dbhost . '\');' . "\n";
        $file .= 'define(\'DB_USER\', \'' . $this->dbuser . '\');' . "\n";
        $file .= 'define(\'DB_PASS\', \'' . $this->dbpass . '\');' . "\n";
        $file .= 'define(\'DB_NAME\', \'' . $this->dbname . '\');' . "\n";
        $file .= 'define(\'PREFIX\', \'' . $this->dbprefix . '\');'."\n\n";

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

        $pass   = md5($this->corepass_1);
        $t1     = $this->dbprefix . 'users';
        $t2     = $this->dbprefix . 'category';
        $t3     = $this->dbprefix . 'config';

        $perms = new permissions;

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
            $this->lang
        );

        $dbh->exec($query);
        
        // wstawiamy pocz±tkowego u¿ytkownika
        $query = sprintf("
            INSERT INTO
                %1\$s
            VALUES
                ('', '%2\$s', '%3\$s', '%4\$s', '%5\$d', 'Y', '', '', '', '', '', '', '', '', '', '')",

            $t1,
            $this->coreuser,
            $pass,
            $this->coremail,
            $bitmask
        );

        $dbh->exec($query);

        if($fp == FALSE) {

            $this->err .= $i18n['main_content'][5];

            $file = str_replace('<', '&lt;', $file);
            $this->err .= "<div class=\"code\">" . str_nl2br($file) . "</div>";
            $this->err .= "<br /><br />";
        } else {

            $this->err .= $i18n['main_content'][4];
        }

        if(!is_writable('../photos')) {
            $photos_dir = realpath('./../') . '/photos/';

            $this->err .= $i18n['main_content'][6];
        }

        $ft->assign('MONIT', $this->err);
        $ft->define('monit_content', "monit_content.tpl");

        $ft->parse('ROWS', ".monit_content");
            
    }
        
        
    function failed_install() {
            
        global $ft;
            
        $ft->define("error_reporting", "error_reporting.tpl");
        $ft->define_dynamic("error_row", "error_reporting");
            
        foreach($this->monit as $error) {
            $ft->assign('ERROR_MONIT', $error);
                
            $ft->parse('ROWS', ".error_row");
        }
        $ft->parse('ROWS', "error_reporting");
    }
        
}
    
?>