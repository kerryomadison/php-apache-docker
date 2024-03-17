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
    case 'GET':
        if ($id !== null) {
            // Return a specific quote by ID
            $result = $quote->read_single($id);
        } elseif ($author_id !== null) {
            // Return quotes by author ID
            $result = $quote->read_by_author($author_id);
        } elseif ($category_id !== null) {
            // Return quotes by category ID
            $result = $quote->read_by_category($category_id);
        } else {
            // Return all quotes
            $result = $quote->read();
        }

        // Check if any quotes were found
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

                // Push to "data"
                array_push($quotes_arr['data'], $quote_item);
            }

            // Turn to JSON & output
            echo json_encode($quotes_arr);
        } else {
            // No quotes found
            http_response_code(404); // Not Found
            echo json_encode(array("message" => "No Quotes Found"));
        }
        break;
    case 'POST':
        // Create a new quote
        // Make sure to validate and sanitize input data
        $data = json_decode(file_get_contents("php://input"));

        // Check if all required fields are present
        if (!empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
            $quote->quote = $data->quote;
            $quote->author_id = $data->author_id;
            $quote->category_id = $data->category_id;

            if ($quote->create()) {
                http_response_code(201); // Created
                echo json_encode(array("message" => "Quote created successfully"));
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(array("message" => "Failed to create quote"));
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(array("message" => "Missing required fields"));
        }
        break;
    case 'PUT':
        // Update an existing quote
        // Make sure to validate and sanitize input data
        $data = json_decode(file_get_contents("php://input"));

        // Check if all required fields are present
        if (!empty($data->id) && !empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
            $quote->id = $data->id;
            $quote->quote = $data->quote;
            $quote->author_id = $data->author_id;
            $quote->category_id = $data->category_id;

            if ($quote->update()) {
                http_response_code(200); // OK
                echo json_encode(array("message" => "Quote updated successfully"));
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(array("message" => "Failed to update quote"));
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(array("message" => "Missing required fields"));
        }
        break;
    case 'DELETE':
        // Delete an existing quote
        // Make sure to validate and sanitize input data
        $data = json_decode(file_get_contents("php://input"));

        // Check if ID is present
        if (!empty($data->id)) {
            $quote->id = $data->id;

            if ($quote->delete()) {
                http_response_code(200); // OK
                echo json_encode(array("message" => "Quote deleted successfully"));
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(array("message" => "Failed to delete quote"));
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(array("message" => "Missing quote ID"));
        }
        break;
    default:
        // Invalid request method
        http_response_code(405); // Method Not Allowed
        echo json_encode(array("message" => "Method not allowed."));
}
?>

