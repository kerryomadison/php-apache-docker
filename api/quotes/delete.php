<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include Database.php
include_once '../../config/Database.php'; 
include_once '../../models/Quote.php';

// Check if the quote ID is provided in the request
$input_data = json_decode(file_get_contents("php://input"));
$quote_id = isset($input_data->id) ? $input_data->id : '';

if (empty($quote_id)) {
    // Quote ID not provided
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Missing quote ID."));
    exit;
}

try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    // Prepare and execute a SQL statement to delete the quote with the provided ID
    $stmt = $pdo->prepare("DELETE FROM quotes WHERE id = :id");
    $stmt->bindParam(':id', $quote_id);
    $stmt->execute();

    // Check if the quote was deleted
    if ($stmt->rowCount() > 0) {
        // Quote deleted successfully
        echo json_encode(array("id" => $quote_id, "message" => "Quote deleted successfully."));
    } else {
        // Quote not found or not deleted
        http_response_code(200); // Not Found
        echo json_encode(array("message" => "No Quotes Found"));
    }
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error deleting quote: " . $e->getMessage()));
}

?>