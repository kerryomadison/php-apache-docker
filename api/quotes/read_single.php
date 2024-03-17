<?php
// Include Database.php
include_once '../Database.php';

// Check if the quote ID is provided in the request
if (isset($_GET['id'])) {
    $quote_id = $_GET['id'];

    try {
        // Create a new instance of the Database class
        $database = new Database();
        $pdo = $database->connect();

        // Prepare and execute a SQL statement to select the quote with the provided ID
        $stmt = $pdo->prepare("SELECT * FROM quotes WHERE id = ?");
        $stmt->execute([$quote_id]);

        // Check if a quote was found
        if ($stmt->rowCount() > 0) {
            // Fetch the quote as an associative array
            $quote = $stmt->fetch(PDO::FETCH_ASSOC);

            // Return the quote as a JSON response
            echo json_encode($quote);
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

