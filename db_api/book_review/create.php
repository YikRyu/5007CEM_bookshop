<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // get database connection
    include_once '../config/database.php';
    
    // instantiate review object
    include_once '../objects/book_review.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $review = new Review($db);
    
    // get posted data
    $data = json_decode(file_get_contents("php://input"));
  
    // make sure data is not empty
    if(
        !empty($data->reviewID) &&
        !empty($data->bookID) &&
        !empty($data->reviewUser) &&
        !empty($data->reviewContent) &&
        !empty($data->reviewRating) &&
        !empty($data->created)
    ){
    
        // set review property values
        $review->reviewID = $data->reviewID;
        $review->bookID = $data->bookID;
        $review->reviewUser = $data->reviewUser;
        $review->reviewContent = $data->reviewContent;
        $review->reviewRating = $data->reviewRating;
        $review->created = $data->created;
    
        // create the review
        if($review->create()){
    
            // set response code - 201 created
            http_response_code(201);
    
            // tell the user
            echo json_encode(array("message" => "Review was created."));
        }
    
        // if unable to create the review, tell the user
        else{
    
            // set response code - 503 service unavailable
            http_response_code(503);
    
            // tell the user
            echo json_encode(array("message" => "Unable to create review."));
        }
    }
    // tell the user data is incomplete
    else{
    
        // set response code - 400 bad request
        http_response_code(400);
    
        // tell the user
        echo json_encode(array("message" => "Unable to create review. Data is incomplete."));
    }
?>
