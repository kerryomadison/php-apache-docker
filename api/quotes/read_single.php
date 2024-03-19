<?php
// Include Database.php
include_once '../Database.php';
include_once '../Quote.php'; // Include the Quote class

// Check if the quote ID is provided in the request
if (isset($_GET['id'])) {
    $quote_id = $_GET['id'];

    try {
        // Create a new instance of the Database class
        $database = new Database();
        $pdo = $database->connect();

        // Instantiate the Quote class
        $quote = new Quote($pdo);

        // Use the read_single method to fetch the quote
        $stmt = $quote->read_single($quote_id);

        // Check if a quote was found
        if ($stmt->rowCount() > 0) {
            // Fetch the quote as an associative array
            $quote_data = $stmt->fetch(PDO::FETCH_ASSOC);

            // Return the quote as a JSON response
            http_response_code(200); // OK
            echo json_encode($quote_data);
        } else {
            // Quote not found
            http_response_code(404); // Not Found
            echo json_encode(array("message" => "Quote not found."));
        }
    } catch (PDOException $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Error retrieving quote: " . $e->getMessage()));
    }
} else {
    // Quote ID not provided
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Missing quote ID."));
}
?>

