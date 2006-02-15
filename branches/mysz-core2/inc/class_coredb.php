<?php

require_once 'class_exceptions.php';
define('DB_HOST', 'localhost');
define('DB_NAME', 'core_new');
define('DB_USER', 'mysz');
define('DB_PASS', 'ttt');

class CoreDB
{
  private static $instance = null;

  private function __construct() {}

  public static function connect($type='mysql')
  {
    if (!isset(self::$instance))
    {
      $c = __CLASS__;
      self::$instance = new $c;
    }
    $conn = null;
    try
    {
      switch ($type)
      {
        case 'mysql':
          $conn =& new PDO(sprintf('mysql:host=%s;dbname=%s', DB_HOST, DB_NAME), DB_USER, DB_PASS);
          break;
        default:
          throw new CESyntaxError('Invalid database type.');
      }
    }
    catch (PDOException $e)
    {
      throw new CEDBError(sprintf('Connection failed: %s.', $e->message));
    }
        
    self::$instance->db = $conn;

    return self::$instance->db;
  }
  
  public function __clone()
  {
    throw new CESyntaxError('Clone not allowed.');
  }
}

$db = CoreDB::connect('mysql');
//$db = $db->db;
$a = $db->query('select * from core_config');
echo $a->rowCount();

?>
