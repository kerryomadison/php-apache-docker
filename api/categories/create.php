<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
//pulled from traversy project
// Include Database.php
include_once '../../config/Database.php'; 
include_once '../../models/Category.php';
// Validate incoming category name
$input_data = json_decode(file_get_contents("php://input"));
$category_name = isset($input_data->category) ? $input_data->category : '';

if (empty($category_name) || strlen($category_name) > 50) {
    // Return an error response indicating that the category name is invalid
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Invalid category name. Category name must be non-empty and less than 50 characters."));
    exit;
}

try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    // Prepare and execute a SQL statement to check if the category already exists
    $stmt_check = $pdo->prepare("SELECT id FROM categories WHERE category = :category");
    $stmt_check->bindParam(':category', $category_name);
    $stmt_check->execute();

    if ($stmt_check->rowCount() > 0) {
        // Category already exists, return an error response
        http_response_code(400); // Bad Request
        echo json_encode(array("message" => "Category already exists."));
        exit;
    }

    // Prepare and execute a SQL statement to insert the category name into the database
    $stmt = $pdo->prepare("INSERT INTO categories (category) VALUES (:category)");
    $stmt->bindParam(':category', $category_name);
    $stmt->execute();

    // Get the ID of the newly created category
    $category_id = $pdo->lastInsertId();

    // Return a success response with the ID and category name of the newly created category
    echo json_encode(array("id" => $category_id, "category" => $category_name, "message" => "Category created successfully."));
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error creating category: " . $e->getMessage()));
}
?>