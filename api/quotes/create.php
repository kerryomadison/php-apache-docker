<?php
// Include Database.php
include_once '../config/Database.php';

// Validate incoming quote text
$quote_text = isset($_POST['quote']) ? $_POST['quote'] : '';
if (empty($quote_text) || strlen($quote_text) > 255) {
    // Return an error response indicating that the quote is invalid
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Invalid quote. Quote must be non-empty and less than 255 characters."));
    exit;
}

try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    // Prepare and execute a SQL statement to insert the quote into the database
    $stmt = $pdo->prepare("INSERT INTO quotes (quote) VALUES (:quote)");
    $stmt->bindParam(':quote', $quote_text);
    $stmt->execute();

    echo json_encode(array("message" => "Quote created successfully."));
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error creating quote: " . $e->getMessage()));
}
?>
