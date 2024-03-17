<?php

class Author {
    // Database connection and table name
    private $conn;
    private $table_name = "authors";

    // Object properties
    public $id;
    public $author;

    // Constructor with $db as database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Read authors
function read() {
    try {
        // Select all query
        $query = "SELECT id, author FROM " . $this->table_name;

        // Prepare query statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    } catch (PDOException $e) {
        // Handle query execution error
        throw new Exception('Error reading authors: ' . $e->getMessage());
    }
}

}
?>
