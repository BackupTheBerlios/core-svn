<?php

class pdo_layer {
    
    public $dbhost  = DB_HOST;
    public $dbname  = DB_NAME;
    public $dbuser  = DB_USER;
    public $dbpass  = DB_PASS;
    
    
    /**
     * Constructor
     * Initialize database connection
     */
    function __construct() {
        
        $dsn = 'mysql:dbname=' . $this->dbname . ';host=' . $this->dbhost;
            
        try {
            $dbh = new PDO($dsn, $this->dbuser, $this->dbpass);
        } catch (PDOException $e) {
            echo 'Wyjatek z³apany: ' . $e->getMessage();
        }
    }
}

?>