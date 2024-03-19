<?php
// Include Database.php
include_once '../config/Database.php';
include_once '../../models/Quote.php';

// Check if the category ID is provided in the request
if (isset($_GET['id'])) {
    $category_id = $_GET['id'];

    try {
        // Create a new instance of the Database class
        $database = new Database();
        $pdo = $database->connect();

        // Instantiate quote object
        $quote = new Quote($pdo);

        // Set the category_id for the quote object
        $quote->category_id = $category_id;

        // Call the read_single method
        $result = $quote->read_single();

        // Check if the quote was found
        if ($result) {
            // Return the quote as a JSON response
            echo json_encode($result);
        } else {
            // Quote not found
            http_response_code(404); // Not Found
            echo json_encode(array("message" => "Quote not found."));
        }
    } catch (PDOException $e) {
        // Return an error response if an exception occurred
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Error reading quote: " . $e->getMessage()));
    }
} else {
    // Category ID not provided
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Missing category ID."));
}

?>


