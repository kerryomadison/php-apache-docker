<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB/connect
$database = new Database();
$db = $database->connect();

// Instantiate quote object
$quote = new Quote($db);

// Handle possible inputted Foreign Keys | Will assign NULL if not inputted.
$quote->author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
$quote->category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

// Quote read query
$result = $quote->read();

// Get row count of returned quotes
$num = $result->rowCount();

if ($num > 0) {
    $quotes_array = $result->fetchAll(PDO::FETCH_ASSOC);

    // Return the quotes as a JSON response
    echo json_encode($quotes_array);
} else {
    // No quotes found
    echo json_encode(array('message' => 'No Quotes Found'));
}
?>
