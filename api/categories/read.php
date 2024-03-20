<?php
// Include Database.php
include_once '../../config/Database.php'; 
include_once '../../models/Category.php';
try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    // Prepare and execute a SQL statement to select all categories
    $stmt = $pdo->query("SELECT * FROM categories");

    // Check if any categories were found
    if ($stmt->rowCount() > 0) {
        // categories array
        $categories_arr = array();
        $categories_arr['data'] = array();

        // Fetch and add each category to the categories array
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $category_item = array(
                'id' => $id,
                'category' => $category
            );

            // Push to "data"
            array_push($categories_arr['data'], $category_item);
        }

        // Set response headers
        header('Content-Type: application/json');

        // Turn to JSON & output
        echo json_encode($categories_arr);
    } else {
        // No categories found
        http_response_code(404); // Not Found
        echo json_encode(array("message" => "No categories found."));
    }
} catch (PDOException $e) {
    // Return an error response if an exception occurred
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error reading categories: " . $e->getMessage()));
}

?>

