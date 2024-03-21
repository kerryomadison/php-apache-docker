<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include Database.php
include_once '../../config/Database.php'; 
include_once '../../models/Author.php';

// Check if the request includes the author ID
$author_id = isset($_GET['id']) ? $_GET['id'] : die();

// Get data from the request body
$data = json_decode(file_get_contents("php://input"));

// Check if the required fields are present
if (!empty($data->author) && strlen($data->author) <= 50) {
    // Proceed with updating the author in the database
    try {
        // Create a new instance of the Database class
        $database = new Database();
        $pdo = $database->connect();

        // Prepare and execute a SQL statement to update the author
        $stmt = $pdo->prepare("UPDATE authors SET author = :author WHERE id = :id");
        $stmt->bindParam(':author', $data->author);
        $stmt->bindParam(':id', $author_id);

        if ($stmt->execute()) {
            // Return the updated author
            $stmt_fetch = $pdo->prepare("SELECT id, author FROM authors WHERE id = ?");
            $stmt_fetch->execute([$author_id]);
            $updated_author = $stmt_fetch->fetch(PDO::FETCH_ASSOC);

            echo json_encode($updated_author);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(array("message" => "Error updating author."));
        }
    } catch (PDOException $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Error updating author: " . $e->getMessage()));
    }
} else {
    http_response_code(200); // Bad Request
    echo json_encode(array("message" => "Missing Required Parameters"));
}
?>