<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

// Include the necessary files
include_once 'config/Database.php';
include_once 'models/Category.php';
include_once 'models/Author.php';
include_once 'models/Quote.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate Category, Author, and Quote objects
$category = new Category($db);
$author = new Author($db);
$quote = new Quote($db);

// Perform operations for each model if needed
// For example, you can retrieve all categories, authors, and quotes here
// and combine them into a single response

// Return a combined response if needed
$response = array(
    'categories' => $category->read(),
    'authors' => $author->read(),
    'quotes' => $quote->read()
);

// Output the combined response as JSON
echo json_encode($response);
?>

