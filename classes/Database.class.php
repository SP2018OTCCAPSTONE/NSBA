<?php
 
/**
 * Database class
 */

class Database
{

  private static $_db;  // singleton connection object

  private function __construct() {}  // disallow creating a new object of the class with new Database()

  private function __clone() {}  // disallow cloning the class

  /**
   * Get the instance of the PDO connection
   *
   * @return DB  PDO connection
   */
  public static function getInstance()
  {
    if (static::$_db === NULL) {

        // EXPERIMENTS
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');
        $database = getenv('DB_DATABASE');
        $username = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');

        $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        static::$_db = new PDO($dsn, $username, $password, $options);

        // Raise exceptions when a database exception occurs
        //static::$_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);// OG
        static::$_db->setAttribute(PDO::ATTR_STATEMENT_CLASS, array("EPDOStatement\EPDOStatement", array(static::$_db)));
    }
    return static::$_db;
  }
 
  /**
   * 
   */
    public static function getDBname() {
        $database = getenv('DB_DATABASE');
        return $database;
    }
}