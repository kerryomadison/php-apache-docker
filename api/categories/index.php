<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate category object
$category = new Category($db);

// Get the request parameters
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Depending on the request method, perform different actions
switch ($method) {
    case 'GET':
        if ($id !== null) {
            // Return a specific category by ID
            $result = $category->read_single($id);
        } else {
            // Return all categories
            $result = $category->read();
        }

        // Check if any categories were found
        if ($result->rowCount() > 0) {
            $categories_arr = array();
            $categories_arr['data'] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $category_item = array(
                    'id' => $id,
                    'category' => $category
                );

                // Push to "data"
                array_push($categories_arr['data'], $category_item);
            }

            // Turn to JSON & output
            echo json_encode($categories_arr);
        } else {
            // No categories found
            http_response_code(404); // Not Found
            echo json_encode(array("message" => "No Categories Found"));
        }
        break;
    default:
        // Invalid request method
        http_response_code(405); // Method Not Allowed
        echo json_encode(array("message" => "Method not allowed."));
}
?>


