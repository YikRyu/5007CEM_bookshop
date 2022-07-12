<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header('Content-Type: application/json');
    
    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/book.php';
    
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
    
    // prepare book object
    $book = new Book($db);
    
    // set ID property of record to read
    $book->bookID = isset($_GET['bookID']) ? $_GET['bookID'] : die();
    
    // read the details of book to be edited
    $book->readOne();
    
    if($book->bookName!=null){
        // create array
        $book_arr = array(
            "bookID" =>  $book->bookID,
            "bookISBN" =>  $book->bookISBN,
            "bookName" =>  $book->bookName,
            "bookAuthor" =>  $book->bookAuthor,
            "bookDesc" => $book->bookDesc,
            "bookPrice" => $book->bookPrice,
            "bookAmount" =>  $book->bookAmount,
            //for genre
            "bookGenre" => $book->bookGenre,
            "genreName" => $book->genreName
    
        );
    
        // set response code - 200 OK
        http_response_code(200);
    
        // make it json format
        echo json_encode($book_arr);
    }
    
    else{
        // set response code - 404 Not found
        http_response_code(404);
    
        // tell the user book does not exist
        echo json_encode(array("message" => "Book does not exist."));
    }
?>