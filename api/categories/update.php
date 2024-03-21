<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include Database.php
include_once '../../config/Database.php'; 
include_once '../../models/Category.php';

// Check if the category ID and new category name are provided in the request body
$input_data = json_decode(file_get_contents("php://input"));

if (!isset($input_data->id) || !isset($input_data->category)) {
    http_response_code(200); // Bad Request
    echo json_encode(array("message" => "Missing Required Parameters"));
    exit;
}

$category_id = $input_data->id;
$new_category_name = $input_data->category;

// Validate the new category name
if (empty($new_category_name) || strlen($new_category_name) > 255) {
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Invalid Category Name"));
    exit;
}

try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    // Prepare and execute a SQL statement to update the category with the provided ID
    $stmt = $pdo->prepare("UPDATE categories SET category = :category WHERE id = :id");
    $stmt->bindParam(':category', $new_category_name);
    $stmt->bindParam(':id', $category_id);
    $stmt->execute();

    // Check if the category was updated
    if ($stmt->rowCount() > 0) {
        // Fetch the updated category from the database
        $stmt_fetch = $pdo->prepare("SELECT id, category FROM categories WHERE id = :id");
        $stmt_fetch->bindParam(':id', $category_id);
        $stmt_fetch->execute();
        $updated_category = $stmt_fetch->fetch(PDO::FETCH_ASSOC);

        // Return the updated category
        echo json_encode($updated_category);
    } else {
        // Category not found or not updated
        http_response_code(404); // Not Found
        echo json_encode(array("message" => "Category Not Found"));
    }
} catch (PDOException $e) {
    // Return an error response if an exception occurred
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error updating category: " . $e->getMessage()));
}
?>