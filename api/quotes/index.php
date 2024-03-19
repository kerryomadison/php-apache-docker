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

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate quote object
$quote = new Quote($db);

// Get the request parameters
$id = isset($_GET['id']) ? $_GET['id'] : null;
$author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

// Depending on the request method, perform different actions
switch ($_SERVER['REQUEST_METHOD']) {
    // GET method
case 'GET':
    $result = $quote->read();

    if ($result->rowCount() > 0) {
        $quotes_arr = array();
        $quotes_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $quote_item = array(
                'id' => $id,
                'quote' => $quote, 
                'author_id' => $author_id,
                'category_id' => $category_id
            );

            array_push($quotes_arr['data'], $quote_item);
        }

        http_response_code(200);
        echo json_encode($quotes_arr);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "No Quotes Found"));
    }
    break;

// POST method
case 'POST':
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
        $quote->quote = $data->quote;
        $quote->author_id = $data->author_id;
        $quote->category_id = $data->category_id;

        if ($quote->create()) {
            http_response_code(201);
            echo json_encode(array("message" => "Quote created successfully"));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Failed to create quote"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Missing required fields"));
    }
    break;

// PUT method
case 'PUT':
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id) && !empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
        $quote->id = $data->id;
        $quote->quote = $data->quote;
        $quote->author_id = $data->author_id;
        $quote->category_id = $data->category_id;

        if ($quote->update()) {
            http_response_code(200);
            echo json_encode(array("message" => "Quote updated successfully"));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Failed to update quote"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Missing required fields"));
    }
    break;

// DELETE method
case 'DELETE':
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id)) {
        $quote->id = $data->id;

        if ($quote->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "Quote deleted successfully"));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Failed to delete quote"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Missing quote ID"));
    }
    break;

default:
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed."));
}
?>
