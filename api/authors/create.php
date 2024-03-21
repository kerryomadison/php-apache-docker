<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include Database.php
include_once '../../config/Database.php'; 
include_once '../../models/Author.php';

// Validate incoming author name
$data = json_decode(file_get_contents("php://input"));

if (empty($data->author) || strlen($data->author) > 50) {
    // Return an error response indicating that the author name is invalid
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Missing Required Parameters"));
    exit;
}

try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    // Prepare and execute a SQL statement to insert the author into the database
    $stmt = $pdo->prepare("INSERT INTO authors (author) VALUES (:author)");
    $stmt->bindParam(':author', $data->author);
    $stmt->execute();

    // Get the ID of the newly created author
    $author_id = $pdo->lastInsertId();

    // Return a success response with the ID and author name of the newly created author
    $response = array("id" => $author_id, "author" => $data->author, "message" => "Author created successfully.");
    echo json_encode($response);
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error creating author: " . $e->getMessage()));
}
?>