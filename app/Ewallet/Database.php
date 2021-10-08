<?php
  declare( strict_types = 1 );

  namespace Ewallet;

  use Dotenv\Dotenv;
  use PDO;
  use PDOException;

  /**
   * TODO: Implement singleton for DB connection
   */
  class Database {
    private PDO    $connection;
    private string $dbHost;
    private string $dbUser;
    private string $dbPassword;
    private string $dbName;
    private string $dsn;

    /**
     * TODO: Use dot env instead of hardcoded credentials
     */
    public function __construct() {
      $this->dbHost     = 'localhost';
      $this->dbName     = 'ewallet';
      $this->dbUser     = 'root';
      $this->dbPassword = 'root';
      $this->dsn        = "mysql:host=$this->dbHost;dbname=$this->dbName";
      try {
        $this->connection = new PDO( $this->dsn, $this->dbUser, $this->dbPassword );
        $this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
      } catch ( PDOException $e ) {
        die( "Failed to connect to DB: $e->getMessage()" );
      }
    }

    public function connect(): PDO {
      return $this->connection;
    }
  }