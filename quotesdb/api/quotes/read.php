<?php
// Include Database.php
include_once '../Database.php';

try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    // Check if author_id or category_id is provided in the request
    if (isset($_GET['author_id'])) {
        $author_id = $_GET['author_id'];

        // Prepare and execute a SQL statement to select quotes by author
        $stmt = $pdo->prepare("SELECT * FROM quotes WHERE author_id = ?");
        $stmt->execute([$author_id]);

        // Fetch and return the quotes as JSON
    } elseif (isset($_GET['category_id'])) {
        $category_id = $_GET['category_id'];

        // Prepare and execute a SQL statement to select quotes by category
        $stmt = $pdo->prepare("SELECT * FROM quotes WHERE category_id = ?");
        $stmt->execute([$category_id]);

        // Fetch and return the quotes as JSON
    } else {
        // Prepare and execute a SQL statement to select all quotes
        $stmt = $pdo->query("SELECT * FROM quotes");

        // Fetch and return the quotes as JSON
    }

    // Check if quotes were found
    if ($stmt->rowCount() > 0) {
        // Fetch all quotes as an associative array
        $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the quotes as a JSON response
        echo json_encode($quotes);
    } else {
        // No quotes found
        http_response_code(404); // Not Found
        echo json_encode(array("message" => "No quotes found."));
    }
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error retrieving quotes: " . $e->getMessage()));
}
?>

