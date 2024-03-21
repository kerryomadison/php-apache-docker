<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
//pulled from traversy project

// Include Database.php
include_once '../../config/Database.php'; 
include_once '../../models/Category.php';
// Check if the request includes the category ID
$category_id = isset($_GET['id']) ? $_GET['id'] : die();

try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    // Prepare and execute a SQL statement to check if the category exists
    $stmt_check = $pdo->prepare("SELECT id FROM categories WHERE id = :id");
    $stmt_check->bindParam(':id', $category_id);
    $stmt_check->execute();

    if ($stmt_check->rowCount() == 0) {
        // Category does not exist, return an error response
        http_response_code(404); // Not Found
        echo json_encode(array("id" => $category_id));
        exit;
    }

    // Prepare and execute a SQL statement to delete the category
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
    $stmt->bindParam(':id', $category_id);

    // Check if the category was deleted
    if ($stmt->rowCount() > 0) {
        // Category deleted successfully
        echo json_encode(array("id" => $category_id, "message" => "Category deleted successfully."));
    } else {
        // Category not found or not deleted
        http_response_code(200); // Not Found
        echo json_encode(array("message" => "No Categories Found"));
    }

} catch (PDOException $e) {
    // Return an error response if an exception occurred
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error deleting category: " . $e->getMessage()));
}
?>