<?php
// Include Database.php
include_once '../Database.php';
include_once '../../models/Quote.php';
try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    $stmt = null;

    // Check if author_id or category_id is provided in the request
    if (isset($_GET['author_id'])) {
        $author_id = $_GET['author_id'];

        // Prepare and execute a SQL statement to select quotes by author
        $stmt = $pdo->prepare("SELECT q.id, q.quote, a.author, c.category 
                                FROM quotes q 
                                JOIN authors a ON q.author_id = a.id 
                                JOIN categories c ON q.category_id = c.id 
                                WHERE q.author_id = ?");
        $stmt->execute([$author_id]);
    } elseif (isset($_GET['category_id'])) {
        $category_id = $_GET['category_id'];

        // Prepare and execute a SQL statement to select quotes by category
        $stmt = $pdo->prepare("SELECT q.id, q.quote, a.author, c.category 
                                FROM quotes q 
                                JOIN authors a ON q.author_id = a.id 
                                JOIN categories c ON q.category_id = c.id 
                                WHERE q.category_id = ?");
        $stmt->execute([$category_id]);
    } else {
        // Prepare and execute a SQL statement to select all quotes
        $stmt = $pdo->query("SELECT q.id, q.quote, a.author, c.category 
                                FROM quotes q 
                                JOIN authors a ON q.author_id = a.id 
                                JOIN categories c ON q.category_id = c.id");
    }

    // Fetch all quotes as an associative array
    $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the quotes as a JSON response
    http_response_code(200); // OK
    echo json_encode($quotes);
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Error retrieving quotes: " . $e->getMessage()));
}
?>




