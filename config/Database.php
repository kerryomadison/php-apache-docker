<?php
class Database {
    //DB params
    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;
    private $conn;
    
    //DB connect
    public function connect(){
        $this->host = getenv('DATABASE_HOST');
        $this->port = getenv('DATABASE_PORT'); //5432 (default port for PostgreSQL)
        $this->db_name = getenv('DATABASE_NAME');
        $this->username = getenv('DATABASE_USERNAME');
        $this->password = getenv('DATABASE_PASSWORD');
    
        $this->conn = null;
        $dsn = "pgsql:host=127.0.0.1;port=5432;dbname=" . getenv('DATABASE_NAME');
        $username = $this->username;
        $password = $this->password;
        try {
            $this->conn = new PDO($dsn, $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            // Throw an exception instead of echoing the error message
            throw new Exception('Connection Error: '.$e->getMessage());
        }
        return $this->conn;
    }
}

?>

