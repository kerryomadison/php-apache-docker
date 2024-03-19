<?php
class Quote {
    private $conn;
    private $table_name = "quotes";

    public $id;
    public $quote;
    public $author_id;
    public $category_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);

        if ($stmt->execute()) {
            return true;
        }

        throw new Exception("Error creating quote: " . $stmt->error);

        return false;
    }

    public function read() {
        // Initialize query
        $query = 'SELECT id, quote, author_id, category_id FROM ' . $this->table_name;

        // Initialize bindings array
        $bindings = array();

        // Check if author_id or category_id is provided in the request
        if (!empty($this->author_id) && !empty($this->category_id)) {
            $query .= ' WHERE author_id = ? AND category_id = ?';
            $bindings[] = $this->author_id;
            $bindings[] = $this->category_id;
        } elseif (!empty($this->author_id)) {
            $query .= ' WHERE author_id = ?';
            $bindings[] = $this->author_id;
        } elseif (!empty($this->category_id)) {
            $query .= ' WHERE category_id = ?';
            $bindings[] = $this->category_id;
        }

        // Prepare the query
        $stmt = $this->conn->prepare($query);

        // Bind parameters if bindings exist
        if (!empty($bindings)) {
            $stmt->execute($bindings);
        } else {
            // Execute the query without parameters
            $stmt->execute();
        }

        // Return the statement
        return $stmt;
    }

    public function read_single() {
        $query = "SELECT id, quote, author_id, category_id FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET quote = :quote, author_id = :author_id, category_id = :category_id WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);

        if ($stmt->execute()) {
            return true;
        }

        throw new Exception("Error updating quote: " . $stmt->error);

        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        throw new Exception("Error deleting quote: " . $stmt->error);

        return false;
    }
}
?>

