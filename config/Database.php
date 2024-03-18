<?php
class Database {
    //DB params
    private $host;
    //private $port;
    private $db_name;
    private $username;
    private $password;
    private $conn;
    
    public function __construct(){
        $this->host = getenv('DATABASE_HOST');
        $this->port = getenv('DATABASE_PORT'); //5432 (default port for PostgreSQL)
        $this->db_name = getenv('DATABASE_NAME');
        $this->username = getenv('DATABASE_USERNAME');
        $this->password = getenv('DATABASE_PASSWORD');
    }
    //DB connect
    public function connect(){
        if($this->conn){
            return $this->conn;
        }
        else{
            $dsn = "pgsql:host={$this->host};dbname={$this->db_name}"; //port={$this->port};
            try {
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->conn;
            } catch(PDOException $e) {
                // Throw an exception instead of echoing the error message
                throw new Exception('Connection Error: '.$e->getMessage());
            }
        }
    }
}

?>

