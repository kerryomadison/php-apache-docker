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
$quote_id = isset($_POST['id']) ? $_POST['id'] : die();

try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    // Prepare and execute a SQL statement to delete the quote with the provided ID
    $stmt = $pdo->prepare("DELETE FROM quotes WHERE id = ?");
    $stmt->execute([$quote_id]);

    // Check if the quote was deleted
    if ($stmt->rowCount() > 0) {
        // Quote deleted successfully
        echo json_encode(array("id" => $quote_id, "message" => "Quote deleted successfully."));
    } else {
        // Quote not found or not deleted
        http_response_code(404); // Not Found
        echo json_encode(array("message" => "Quote not found."));
    }
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error deleting quote: " . $e->getMessage()));
}
?>