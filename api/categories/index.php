<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

// Include Database.php
include_once '../../config/Database.php';
include_once '../../models/Quote.php';
include_once '../../Category.php';
// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate quote object
$category = new Category($db);

// Get the request parameters
$id = isset($_GET['id']) ? $_GET['id'] : null;
$author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

// Depending on the request method, perform different actions
switch ($_SERVER['REQUEST_METHOD']) {
    // GET method
case 'GET':
    $result = $category->read();

    if ($result->rowCount() > 0) {
        $categories_arr = array();
        $categories_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $category_item = array(
                'id' => $id,
                'category_id' => $category_name
            );

            array_push($categories_arr['data'], $category_item);
        }

        http_response_code(200);
        echo json_encode($category_arr);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "No Quotes Found"));
    }
    break;

// POST method
case 'POST':
    $data = json_decode(file_get_contents("php://input"));

    // Check if the required fields are present
    if (!empty($data->category_name)) {
        // Set the category name from the request data
        $category->category_name = $data->category_name;

        // Create the category
        if ($category->create()) {
            // Category created successfully
            http_response_code(201);
            echo json_encode(array("message" => "Category created successfully"));
        } else {
            // Failed to create category
            http_response_code(500);
            echo json_encode(array("message" => "Failed to create category"));
        }
    } else {
        // Missing required fields
        http_response_code(400);
        echo json_encode(array("message" => "Missing category_name field"));
    }
    break;


// PUT method
case 'PUT':
    $data = json_decode(file_get_contents("php://input"));

    // Check if the required fields are present
    if (!empty($data->id) && !empty($data->category_name)) {
        // Set the category id and name from the request data
        $category->id = $data->id;
        $category->category_name = $data->category_name;

        // Update the category
        if ($category->update()) {
            // Category updated successfully
            http_response_code(200);
            echo json_encode(array("message" => "Category updated successfully"));
        } else {
            // Failed to update category
            http_response_code(500);
            echo json_encode(array("message" => "Failed to update category"));
        }
    } else {
        // Missing required fields
        http_response_code(400);
        echo json_encode(array("message" => "Missing id or category_name field"));
    }
    break;


// DELETE method
case 'DELETE':
    $data = json_decode(file_get_contents("php://input"));

    // Check if the id field is present
    if (!empty($data->id)) {
        // Set the category id from the request data
        $category->id = $data->id;

        // Delete the category
        if ($category->delete()) {
            // Category deleted successfully
            http_response_code(200);
            echo json_encode(array("message" => "Category deleted successfully"));
        } else {
            // Failed to delete category
            http_response_code(500);
            echo json_encode(array("message" => "Failed to delete category"));
        }
    } else {
        // Missing id field
        http_response_code(400);
        echo json_encode(array("message" => "Missing id field"));
    }
    break;
}
?>