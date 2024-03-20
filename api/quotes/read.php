<?php
// Include Database.php
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

try {
    // Create a new instance of the Database class
    $database = new Database();
    $pdo = $database->connect();

    // Instantiate Quote object
    $quote = new Quote($pdo);

    // Read quotes
    $result = $quote->read();
    $num = $result->rowCount();

    if ($num > 0) {
        $quotes_array = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $quote_item = array(
                'id' => $id,
                'quote' => $quote,
                'author_id' => $author_id,
                'category_id' => $category_id
            );

            array_push($quotes_array, $quote_item);
        }

        // Set response code to 200 OK
        http_response_code(200);
        echo json_encode($quotes_array);
    } else {
        // Set response code to 404 Not Found
        http_response_code(404);
        echo json_encode(
            array('message' => 'No quotes found.')
        );
    }
} catch (PDOException $e) {
    // Set response code to 500 Internal Server Error
    http_response_code(500);
    echo json_encode(
        array('message' => 'Error reading quotes: ' . $e->getMessage())
    );
}
?>
