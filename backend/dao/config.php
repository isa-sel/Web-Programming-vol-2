<?php
class Database {
  // Change as needed (127.0.0.1:3306 for MySQL80, or 127.0.0.1:3307 for XAMPP)
  private static $host = '127.0.0.1';
  private static $port = '3306';
  private static $dbName = 'handball_management_system';
  private static $username = 'root';
  private static $password = '';
  private static $connection = null;

  public static function connect() {
    if (self::$connection === null) {
      try {
        $dsn = "mysql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$dbName . ";charset=utf8mb4";
        self::$connection = new PDO(
          $dsn,
          self::$username,
          self::$password,
          [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
          ]
        );
      } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
      }
    }
    return self::$connection;
  }
}
