<?php
// Include Database.php
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    // Instantiate quote object
    $quote = new Quote($pdo);

    // Check if the quote ID is provided in the request
    if (isset($_GET['id'])) {
        $quote_id = $_GET['id'];

        // Set the quote ID
        $quote->id = $quote_id;

        // Read single quote
        $result = $quote->read_single();

        if ($result === false) {
            // No quote found
            http_response_code(404); // Not Found
            echo json_encode(array("message" => "No Quote Found"));
        } else {
            // Quote found, return the quote as a JSON response
            $quote_data = $result->fetch(PDO::FETCH_ASSOC);

            // Fetch author details
            $author_id = $quote_data['author_id'];
            $author_query = "SELECT author FROM authors WHERE id = :author_id";
            $author_stmt = $pdo->prepare($author_query);
            $author_stmt->bindParam(':author_id', $author_id);
            $author_stmt->execute();
            $author_row = $author_stmt->fetch(PDO::FETCH_ASSOC);
            $author = $author_row['author'];

            // Fetch category details
            $category_id = $quote_data['category_id'];
            $category_query = "SELECT category FROM categories WHERE id = :category_id";
            $category_stmt = $pdo->prepare($category_query);
            $category_stmt->bindParam(':category_id', $category_id);
            $category_stmt->execute();
            $category_row = $category_stmt->fetch(PDO::FETCH_ASSOC);
            $category = $category_row['category'];

            $quote_data['author'] = $author;
            $quote_data['category'] = $category;

            echo json_encode($quote_data);
        }
    } else {
        // Quote ID not provided
        http_response_code(400); // Bad Request
        echo json_encode(array("message" => "Missing quote ID."));
    }
} catch (PDOException $e) {
    // Return an error response if an exception occurred
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error reading quote: " . $e->getMessage()));
}
?>