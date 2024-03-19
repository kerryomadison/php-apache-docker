<?php
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Check if the quote ID is provided in the request
if (isset($_GET['id'])) {
    $quote_id = $_GET['id'];

    // Instantiate DB/connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate quote object
    $quote = new Quote($db);

    // Set the quote ID
    $quote->id = $quote_id;

    // Read single quote
    $result = $quote->read_single();

    // Check if a quote was found
    if ($result->rowCount() == 1) {
        $quote_data = $result->fetch(PDO::FETCH_ASSOC);

        // Return the quote as a JSON response
        http_response_code(200); // OK
        echo json_encode($quote_data);
    } else {
        // Quote not found
        http_response_code(404); // Not Found
        echo json_encode(array('message' => 'Quote not found.'));
    }
} else {
    // Quote ID not provided
    http_response_code(400); // Bad Request
    echo json_encode(array('message' => 'Missing quote ID.'));
}
?>


