<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/book.php';
    
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
    
    // prepare book object
    $book = new Book($db);
    
    // get id of book to be edited
    $data = json_decode(file_get_contents("php://input"));
    
    // set ID property of book to be edited
    $book->bookID = $data->bookID;
    
    // set book property values
    $book->bookAmount = $data->bookAmount;
    
    // update the book
    if($book->update()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "Book was updated."));
    }
    
    // if unable to update the book, tell the user
    else{
    
        // set response code - 503 service unavailable
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update the book."));
    }

?>