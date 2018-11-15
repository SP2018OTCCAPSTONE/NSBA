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
        static::$_db = new PDO($dsn, $username, $password);

        // Raise exceptions when a database exception occurs
        static::$_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return static::$_db;
  }
  // ************************************** THESE ARE UNUSED ******************************************************
    // Might have to call these like Database::getOne()??
    // Connects to the database and returns a specific mySQL query as array
    public static function getOne($query, array $binds = [], $db) {
        $statement = $conn->prepare($query);
        foreach($binds as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result;
    }
    // Connects to the database and returns a table as array
    public static function getMany($query, array $binds = [], $db) {
        $statement = $conn->prepare($query);
        foreach($binds as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
        $results = $statement->fetchAll();
        $statement->closeCursor();
        return $results;
    }

    public static function getDBname() {
        $database = getenv('DB_DATABASE');
        return $database;
    }
}