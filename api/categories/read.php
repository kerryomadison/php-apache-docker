<?php
// Include Database.php
include_once '../../config/Database.php'; 
include_once '../../models/Category.php';

try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    // Instantiate Category object
    $category = new Category($pdo);

    // Read categories
    $result = $category->read();

    // Get row count of returned categories
    $num = $result->rowCount();

    if ($num > 0) {
        $categories_array = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $category_item = array(
                'id' => $row['id'],
                'category' => $row['category']
            );

            // Store item for results
            array_push($categories_array, $category_item);
        }

        http_response_code(200); // OK
        echo json_encode($categories_array);
    } else {
        // No categories found
        http_response_code(404); // Not Found
        echo json_encode(array('message' => 'No Categories Found'));
    }
} catch (PDOException $e) {
    // Return an error response if an exception occurred
    http_response_code(500); // Internal Server Error
    echo json_encode(array('message' => 'Error reading categories: ' . $e->getMessage()));
}
?>
