<?php
// Include Database.php
include_once '../Database.php';

// Check if the quote ID is provided in the request
if (isset($_POST['id'])) {
    $quote_id = $_POST['id'];

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
            echo json_encode(array("message" => "Quote deleted successfully."));
        } else {
            // Quote not found or not deleted
            http_response_code(404); // Not Found
            echo json_encode(array("message" => "Quote not found or not deleted."));
        }
    } catch (PDOException $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Error deleting quote: " . $e->getMessage()));
    }
} else {
    // Quote ID not provided
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Missing quote ID."));
}
?>