<?php
// Include Database.php
include_once '../Database.php';

// Check if the author ID is provided in the request
if (!isset($_GET['id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Missing author ID."));
    exit;
}

// Get the author ID from the request
$author_id = $_GET['id'];

try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    // Prepare and execute a SQL statement to select the author from the database
    $stmt = $pdo->prepare("SELECT * FROM authors WHERE id = :id");
    $stmt->bindParam(':id', $author_id);
    $stmt->execute();

    // Check if a row was returned
    if ($stmt->rowCount() > 0) {
        $author = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($author);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(array("message" => "Author not found."));
    }
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error fetching author: " . $e->getMessage()));
}




?>
