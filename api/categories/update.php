<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include Database.php
include_once '../../config/Database.php'; 
include_once '../../models/Category.php';

// Check if the category ID and new category name are provided in the request
if (!isset($_POST['id']) || !isset($_POST['category'])) {
    http_response_code(200); // Bad Request
    echo json_encode(array("message" => "Missing Required Parameters"));
    exit;
}

$category_id = $_POST['id'];
$new_category_name = $_POST['category'];

try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    // Prepare and execute a SQL statement to update the category with the provided ID
    $stmt = $pdo->prepare("UPDATE categories SET category = ? WHERE id = ?");
    $stmt->execute([$new_category_name, $category_id]);

    // Check if the category was updated
    if ($stmt->rowCount() > 0) {
        // Fetch the updated category from the database
        $stmt_fetch = $pdo->prepare("SELECT id, category FROM categories WHERE id = ?");
        $stmt_fetch->execute([$category_id]);
        $updated_category = $stmt_fetch->fetch(PDO::FETCH_ASSOC);

        // Return the updated category
        echo json_encode($updated_category);
    } else {
        // Category not found or not updated
        http_response_code(200); // Not Found
        echo json_encode(array("message" => "No Categories Found"));
    }
} catch (PDOException $e) {
    // Return an error response if an exception occurred
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error updating category: " . $e->getMessage()));
}
?>