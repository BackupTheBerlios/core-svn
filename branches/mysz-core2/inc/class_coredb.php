<?php
// $Id$

class CoreDB {
    
    private static $instance = null;
    
    private function __construct() {}
    
    public static function connect($type='mysql') {
        if(!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        
        $conn = null;
        
        try {
            switch($type) {
                case 'mysql':
                    $conn =& new PDO(sprintf(
                        'mysql:host=%s;dbname=%s', DB_HOST, DB_NAME), 
                        DB_USER, 
                        DB_PASS
                    );
                break;
            default:
                throw new CESyntaxError('Invalid database type.');
            }
        } catch(PDOException $e) {
            throw new CEDBError(sprintf('Connection failed: %s.', $e->getMessage()));
        }
        
        self::$instance->db = $conn;
        return self::$instance->db;
    }
    
    public function __clone() {
        throw new CESyntaxError('Clone not allowed.');
    }
}

// vim: expandtab:shiftwidth=4:softtabstop=4:tabstop=4
?>