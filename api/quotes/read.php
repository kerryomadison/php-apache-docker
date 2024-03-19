<?php

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
    $quotes_array = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // Fetch author and category details based on author_id and category_id
        $author_id = $row['author_id'];
        $category_id = $row['category_id'];

        // Fetch author details
        $author_query = "SELECT author FROM authors WHERE id = :author_id";
        $author_stmt = $db->prepare($author_query);
        $author_stmt->bindParam(':author_id', $author_id);
        $author_stmt->execute();
        $author_row = $author_stmt->fetch(PDO::FETCH_ASSOC);
        $author = $author_row['author'];

        // Fetch category details
        $category_query = "SELECT category FROM categories WHERE id = :category_id";
        $category_stmt = $db->prepare($category_query);
        $category_stmt->bindParam(':category_id', $category_id);
        $category_stmt->execute();
        $category_row = $category_stmt->fetch(PDO::FETCH_ASSOC);
        $category = $category_row['category'];

        $single_quote = array(
            'id' => $row['id'],
            'quote' => $row['quote'],
            'author' => $author,
            'category' => $category
        );

        // Store item for results
        array_push($quotes_array, $single_quote);
    }

    // Return the quotes as a JSON response
    http_response_code(200); // OK
    echo json_encode($quotes_array);
} else {
    http_response_code(404); // Not Found
    echo json_encode(array('message' => 'No Quotes Found'));
}







