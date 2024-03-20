<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include Database.php
include_once '../../config/Database.php'; 
include_once '../../models/Quote.php';

// Validate incoming quote text, author ID, and category ID
$data = json_decode(file_get_contents("php://input"));

if (empty($data->quote) || strlen($data->quote) > 255 || empty($data->author_id) || empty($data->category_id)) {
    // Return an error response indicating that the input data is invalid
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Missing Required parameters"));
    exit;
}

try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    // Prepare and execute a SQL statement to insert the quote into the database
    $stmt = $pdo->prepare("INSERT INTO quotes (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)");
    $stmt->bindParam(':quote', $data->quote);
    $stmt->bindParam(':author_id', $data->author_id);
    $stmt->bindParam(':category_id', $data->category_id);
    $stmt->execute();

    // Retrieve the ID of the newly inserted quote
    $quote_id = $pdo->lastInsertId();

    // Construct and return a JSON response with the new quote's ID, quote text, author ID, and category ID
    echo json_encode(array("id" => $quote_id, "quote" => $data->quote, "author_id" => $data->author_id, "category_id" => $data->category_id));
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error creating quote: " . $e->getMessage()));
}
?>


