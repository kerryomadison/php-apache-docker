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

// Include Category.php
include_once '../../models/Category.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate category object
$category = new Category($db);

// Depending on the request method, include the corresponding file
switch ($method){
    case 'GET':
        if (isset($_GET['id'])) {
            include_once 'read_single.php';
        } else {
            include_once 'read.php';
        }
        break;
    case 'POST':
        include_once 'create.php';
        break;
    case 'PUT':
        include_once 'update.php';
        break;
    case 'DELETE':
        include_once 'delete.php';
        break;
    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(array("message" => "Method not allowed."));
}
?>