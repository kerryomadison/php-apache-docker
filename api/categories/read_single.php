<?php
// Include Database.php
include_once '../../config/Database.php';
include_once '../../models/Category.php';
// Check if the category ID is provided in the request
if (isset($_GET['id'])) {
    $category_id = $_GET['id'];

    try {
        // Create a new instance of the Database class
        $database = new Database();
        $pdo = $database->connect();

        // Prepare and execute a SQL statement to select the category with the provided ID
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$category_id]);

        // Check if the category was found
        if ($stmt->rowCount() == 1) {
            // Fetch and return the category as a JSON response
            $category = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($category);
        } else {
            // Category not found
            http_response_code(404); // Not Found
            echo json_encode(array("message" => "category_id Not Found."));
        }
    } catch (PDOException $e) {
        // Return an error response if an exception occurred
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Error reading category: " . $e->getMessage()));
    }
} else {
    // Category ID not provided
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Missing category ID."));
}
?>