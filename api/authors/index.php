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
include_once '../../models/Author.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate author object
$author = new Author($db);

// Get the request parameters
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Depending on the request method, perform different actions
switch ($_SERVER['REQUEST_METHOD']) {
    // GET method
    case 'GET':
        if ($id) {
            // Get single author
            $author->id = $id;
            $result = $author->read_single();

            if ($result) {
                // Author found
                http_response_code(200);
                echo json_encode($result);
            } else {
                // Author not found
                http_response_code(404);
                echo json_encode(array("message" => "Author not found"));
            }
        } else {
            // Get all authors
            $result = $author->read();

            if ($result->rowCount() > 0) {
                $authors_arr = array();
                $authors_arr['data'] = array();

                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);

                    $author_item = array(
                        'id' => $id,
                        'author' => $author
                    );

                    array_push($authors_arr['data'], $author_item);
                }

                http_response_code(200);
                echo json_encode($authors_arr);
            } else {
                // No authors
                http_response_code(404);
                echo json_encode(array("message" => "No Authors Found"));
            }
        }
        break;

    // POST method
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->author)) {
            $author->author = $data->author;

            if ($author->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Author created successfully"));
            } else {
                http_response_code(500);
                echo json_encode(array("message" => "Failed to create author"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Missing author field"));
        }
        break;

    // PUT method
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->id) && !empty($data->author)) {
            $author->id = $data->id;
            $author->author = $data->author;

            if ($author->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Author updated successfully"));
            } else {
                http_response_code(500);
                echo json_encode(array("message" => "Failed to update author"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Missing id or author field"));
        }
        break;

    // DELETE method
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->id)) {
            $author->id = $data->id;

            if ($author->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Author deleted successfully"));
            } else {
                http_response_code(500);
                echo json_encode(array("message" => "Failed to delete author"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Missing id field"));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed."));
}
?>
