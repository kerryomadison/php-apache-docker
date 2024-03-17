<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];

    if($method==='OPTIONS'){
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
        exit();
    } //put at the beginning of ALL index.php in all folders. 
    
    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate author object
    $author = new Author($db);

    // Author query
    $result = $author->read();

    // Get row count
    $num = $result->rowCount();

    // Check if any authors
    if ($num > 0) {
    // Authors array
    $authors_arr = array();
    $authors_arr['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $author_item = array(
        'id' => $id,
        'author' => $author
        );

        // Push to "data"
        array_push($authors_arr['data'], $author_item);
    }

    // Turn to JSON & output
    echo json_encode($authors_arr);
    } else {
    // No authors
    echo json_encode(
        array('message' => 'No Authors Found')
    );
    }
    ?>