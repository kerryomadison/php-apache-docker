<?php
// Include Database.php
include_once '../config/Database.php';
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
        echo json_encode(array("message" => "Category not found."));
        exit;
    }

    // Prepare and execute a SQL statement to delete the category
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
    $stmt->bindParam(':id', $category_id);

    if ($stmt->execute()) {
        // Return a success response with the ID of the deleted category
        echo json_encode(array("id" => $category_id, "message" => "Category deleted successfully."));
    } else {
        // Return an error response if the deletion failed
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Unable to delete category."));
    }
} catch (PDOException $e) {
    // Return an error response if an exception occurred
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error deleting category: " . $e->getMessage()));
}
?>

