<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include Database.php
include_once '../../config/Database.php'; 
include_once '../../models/Author.php';
try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    // Prepare and execute a SQL statement to select all authors from the database
    $stmt = $pdo->query("SELECT * FROM authors");

    // Check if any authors were found
    if ($stmt->rowCount() > 0) {
        // Fetch all authors as an associative array
        $authors = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($authors);
    } else {
        // No authors found
        http_response_code(200); // Not Found
        echo json_encode(array("message" => "No authors found."));
    }
} catch (PDOException $e) {
    // Error fetching authors
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error fetching authors: " . $e->getMessage()));
}
?>