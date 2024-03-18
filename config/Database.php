<?php
class Database {
    //DB params
    private $host;
    private $port;
    private $dbname;
    private $username;
    private $password;
    private $conn;
    
    public function __construct(){
        $url = parse_url(getenv('DATABASE_URL'));

        $this->host = $url['host'];
        $this->port = $url['port'];
        $this->dbname = ltrim($url['path'], '/');
        $this->username = $url['user'];
        $this->password = $url['pass'];
    }
    //DB connect
    public function connect(){
        if($this->conn){
            return $this->conn;
        }
        else{
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname};sslmode=require";
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

