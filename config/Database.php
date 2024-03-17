<?php
class Database{
    //DB params
    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;
    private $conn;
    
    //DB connect
public function connect(){

    $this->host= getenv('DATABASE_HOST');
    $this->port=getenv('DATABASE_PORT'); //5432? 
    $this->db_name=getenv('DATABASE_NAME');
    $this->username=getenv('DATABASE_USERNAME');
    $this->password=getenv('DATABASE_PASSWORD');
    
    $this->conn=null;
    $dsn = "pgsql:host=" . getenv('DATABASE_HOST') . ";port=" . getenv('DATABASE_PORT') . ";dbname=" . getenv('DATABASE_NAME');
    $username = getenv('DATABASE_USER');
    $password = getenv('DATABASE_PASSWORD');
    try{
        $this->conn = new PDO($dsn, $username, $password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        // Throw an exception instead of echoing the error message
        throw new Exception('Connection Error: '.$e->getMessage());
    }
    return $this->conn;
}
}
?>

