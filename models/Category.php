<?php
class Category {
  // DB stuff
  private $conn;
  private $table = 'categories';

  // Category properties
  public $id;
  public $category_name; 

  // Constructor with DB
  public function __construct($db) {
      $this->conn = $db;
  }

  // Get categories
  public function read() {
      // Create query
      $query = 'SELECT
                  id,
                  category_name AS category
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

  // Create category
  public function create() {
      // Create query
      $query = 'INSERT INTO ' . $this->table . '
                SET
                  category_name = :category_name';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind data
      $stmt->bindParam(':category_name', $this->category_name);

      // Execute query
      if($stmt->execute()) {
          return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
  }

  // Update category
  public function update() {
      // Create query
      $query = 'UPDATE ' . $this->table . '
                SET
                  category_name = :category_name
                WHERE
                  id = :id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind data
      $stmt->bindParam(':category_name', $this->category_name);
      $stmt->bindParam(':id', $this->id);

      // Execute query
      if($stmt->execute()) {
          return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
  }

  // Delete category
  public function delete() {
      // Create query
      $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind data
      $stmt->bindParam(':id', $this->id);

      // Execute query
      if($stmt->execute()) {
          return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
  }
}
?>
