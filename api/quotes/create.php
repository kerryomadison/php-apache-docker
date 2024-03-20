<?php
// Include Database.php
include_once '../../config/Database.php'; 
include_once '../../models/Quote.php';

// Validate incoming quote text, author ID, and category ID
$quote_text = isset($_POST['quote']) ? $_POST['quote'] : '';
$author_id = isset($_POST['author_id']) ? $_POST['author_id'] : null;
$category_id = isset($_POST['category_id']) ? $_POST['category_id'] : null;

if (empty($quote_text) || strlen($quote_text) > 255 || empty($author_id) || empty($category_id)) {
    // Return an error response indicating that the input data is invalid
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Invalid input data."));
    exit;
}

try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    // Prepare and execute a SQL statement to insert the quote into the database
    $stmt = $pdo->prepare("INSERT INTO quotes (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)");
    $stmt->bindParam(':quote', $quote_text);
    $stmt->bindParam(':author_id', $author_id);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->execute();

    // Retrieve the ID of the newly inserted quote
    $quote_id = $pdo->lastInsertId();

    // Construct and return a JSON response with the new quote's ID, quote text, author ID, and category ID
    echo json_encode(array("id" => $quote_id, "quote" => $quote_text, "author_id" => $author_id, "category_id" => $category_id));
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error creating quote: " . $e->getMessage()));
}
?>
