<?php
// Include Quote.php
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Check if the quote ID is provided in the request
if (isset($_GET['id'])) {
    $quote_id = $_GET['id'];

    try {
        // Create a new instance of the Database class
        $database = new Database();
        $pdo = $database->connect();

        // Instantiate Quote object
        $quote = new Quote($pdo);
        $quote->id = $quote_id;

        // Read single quote
        $result = $quote->read_single();

        if ($result === false) {
            // No quote found
            http_response_code(404); // Not Found
            echo json_encode(array("message" => "No Quotes Found"));
        } else {
            // Quote found, return the quote as a JSON response
            $quote_data = $result->fetch(PDO::FETCH_ASSOC);
            echo json_encode($quote_data);
        }
    } catch (PDOException $e) {
        // Return an error response if an exception occurred
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Error reading quote: " . $e->getMessage()));
    }
} else {
    // Quote ID not provided
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Missing quote ID."));
}
?>



