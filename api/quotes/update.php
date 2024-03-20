<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include Database.php
include_once '../../config/Database.php'; 
include_once '../../models/Quote.php';
// Get the quote ID from the request
$quote_id = isset($_POST['id']) ? $_POST['id'] : null;
if (!$quote_id) {
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Missing quote ID."));
    exit;
}

// Get the updated quote and author data from the request
$quote_text = isset($_POST['quote']) ? $_POST['quote'] : '';
$author_id = isset($_POST['author_id']) ? $_POST['author_id'] : null;
$category_id = isset($_POST['category_id']) ? $_POST['category_id'] : null;

if (empty($quote_text) || strlen($quote_text) > 255 || !$author_id || !$category_id) {
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Invalid data."));
    exit;
}

try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    // Prepare and execute a SQL statement to update the quote
    $stmt = $pdo->prepare("UPDATE quotes SET quote = :quote, author_id = :author_id, category_id = :category_id WHERE id = :id");
    $stmt->bindParam(':quote', $quote_text);
    $stmt->bindParam(':author_id', $author_id);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':id', $quote_id);
    $stmt->execute();

    // Check if the quote was updated
    if ($stmt->rowCount() > 0) {
        http_response_code(200); // OK
        echo json_encode(array("message" => "Quote updated successfully."));
    } else {
        http_response_code(404); // Not Found
        echo json_encode(array("message" => "Quote not found."));
    }
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error updating quote: " . $e->getMessage()));
}
?>