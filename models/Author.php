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

    // Read all authors
    public function read() {
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

    // Read single author
    public function read_single() {
        try {
            // Select single query
            $query = "SELECT id, author FROM " . $this->table_name . " WHERE id = :id";

            // Prepare query statement
            $stmt = $this->conn->prepare($query);

            // Bind ID parameter
            $stmt->bindParam(':id', $this->id);

            // Execute query
            $stmt->execute();

            // Check if author exists
            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            // Handle query execution error
            throw new Exception('Error reading author: ' . $e->getMessage());
        }
    }

    // Create author
    public function create() {
        try {
            // Insert query
            $query = "INSERT INTO " . $this->table_name . " (author) VALUES (:author)";

            // Prepare query
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':author', $this->author);

            // Execute query
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Handle query execution error
            throw new Exception('Error creating author: ' . $e->getMessage());
        }
    }


    // Update author
    public function update() {
        try {
            // Update query
            $query = "UPDATE " . $this->table_name . " SET author = :author WHERE id = :id";

            // Prepare query
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':author', $this->author);

            // Execute query
            if ($stmt->execute()) {
                // Debug statement
                echo json_encode(array("message" => "Author updated successfully."));
                return true;
            } else {
                // Debug statement
                echo json_encode(array("message" => "Failed to update author."));
                return false;
            }
        } catch (PDOException $e) {
            // Handle query execution error
            throw new Exception('Error updating author: ' . $e->getMessage());
        }
    }
    // Delete author
    public function delete() {
        try {
            // Delete query
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

            // Prepare query
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':id', $this->id);

            // Execute query
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Handle query execution error
            throw new Exception('Error deleting author: ' . $e->getMessage());
        }
    }
}
?>