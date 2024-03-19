<?php
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
            http_response_code(200); // OK
            echo json_encode(array("message" => "Author updated successfully."));
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(array("message" => "Unable to update author."));
        }
    } catch (PDOException $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Error updating author: " . $e->getMessage()));
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Invalid author name."));
}
?>