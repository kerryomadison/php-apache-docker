<?php
// Include Database.php
include_once '../../config/Database.php'; 
include_once '../../models/Author.php';
// Validate incoming author name
$author_name = isset($_POST['author']) ? $_POST['author'] : '';
if (empty($author_name) || strlen($author_name) > 50) {
    // Return an error response indicating that the author name is invalid
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Invalid author. Author must be non-empty and less than 50 characters."));
    exit;
}

try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    // Prepare and execute a SQL statement to insert the author into the database
    $stmt = $pdo->prepare("INSERT INTO authors (author) VALUES (:author)");
    $stmt->bindParam(':author', $author_name);
    $stmt->execute();

    echo json_encode(array("message" => "Author created successfully."));
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error creating author: " . $e->getMessage()));
}
?>

?>
