<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    // include database and object files
    include_once '../config/core.php';
    include_once '../shared/utilities.php';
    include_once '../config/database.php';
    include_once '../objects/book_review.php';
    
    // utilities
    $utilities = new Utilities();
    
    // instantiate database and review object
    $database = new Database();
    $db = $database->getConnection();
    
    // initialize object
    $review = new Review($db);
    
    // query reviewss
    $stmt = $review->readPaging($from_record_num, $records_per_page);
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if($num>0){
    
        // reviews array
        $reviews_arr=array();
        $reviews_arr["records"]=array();
        $reviews_arr["paging"]=array();
    
        // retrieve our table contents
        // fetch() is faster than fetchAll()
        // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
            extract($row);
    
            $review_item=array(
                "reviewID" => $reviewID,
                "reviewUser" => $reviewUser,
                "reviewContent" => html_entity_decode($reviewContent),
                "reviewRating" => $reviewRating,
                "created" => $created,
                //for genre
                "bookID" => $bookID,
                "bookName" => $bookName
            );
    
            array_push($reviews_arr["records"], $review_item);
        }
    
    
        // include paging
        $total_rows=$review->count();
        $page_url="{$home_url}book_review/read_paging.php?";
        $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
        $reviews_arr["paging"]=$paging;
    
        // set response code - 200 OK
        http_response_code(200);
    
        // make it json format
        echo json_encode($reviews_arr);
    }
    
    else{
    
        // set response code - 404 Not found
        http_response_code(404);
    
        // tell the user reviews does not exist
        echo json_encode(
            array("message" => "No reviews found.")
        );
    }
?>