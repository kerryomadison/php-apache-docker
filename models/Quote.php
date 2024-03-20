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
        $query = 'SELECT q.id, q.quote, a.author, c.category FROM ' . $this->table_name . ' q';
        $query .= ' LEFT JOIN authors a ON q.author_id = a.id';
        $query .= ' LEFT JOIN categories c ON q.category_id = c.id';
        
        $where = array();
        $bindings = array();
    
        if (!empty($this->author_id)) {
            $where[] = 'q.author_id = ?';
            $bindings[] = $this->author_id;
        }
    
        if (!empty($this->category_id)) {
            $where[] = 'q.category_id = ?';
            $bindings[] = $this->category_id;
        }
    
        if (!empty($where)) {
            $query .= ' WHERE ' . implode(' AND ', $where);
        }
    
        $stmt = $this->conn->prepare($query);
    
        if (!empty($bindings)) {
            $stmt->execute($bindings);
        } else {
            $stmt->execute();
        }
    
        return $stmt;
    }    

    // In Quote.php
    public function read_single() {
    $query = 'SELECT id, quote, author, category FROM quotes WHERE id = :id';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $this->id);
    $stmt->execute();

    // Fetch quote as an associative array
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row; // Return the fetched quote
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

