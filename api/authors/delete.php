<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include Database.php
include_once '../../config/Database.php'; 
include_once '../../models/Author.php';

// Check if the author ID is provided in the request
$input_data = json_decode(file_get_contents("php://input"));
$author_id = isset($input_data->id) ? $input_data->id : '';

if (empty($author_id)) {
    // Author ID not provided
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Missing author ID."));
    exit;
}

try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    // Prepare and execute a SQL statement to delete the author with the provided ID
    $stmt = $pdo->prepare("DELETE FROM authors WHERE id = :id");
    $stmt->bindParam(':id', $author_id);
    $stmt->execute();

    // Check if the author was deleted
    if ($stmt->rowCount() > 0) {
        // Author deleted successfully
        echo json_encode(array("id" => $author_id, "message" => "Author deleted successfully."));
    } else {
        // Author not found or not deleted
        http_response_code(200); // Not Found
        echo json_encode(array("message" => "Author Not Found"));
    }
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error deleting author: " . $e->getMessage()));
}

?>