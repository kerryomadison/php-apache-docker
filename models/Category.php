<?php
class Category {
    // DB stuff
    private $conn;
    private $table = 'categories';

    // Category properties
    public $id;
    public $category;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get categories
    public function read() {
        // Create query
        $query = 'SELECT
                    id,
                    category
                  FROM
                    ' . $this->table . '
                  ORDER BY
                    id DESC';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }
}
?>
