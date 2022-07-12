<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/user.php';
    
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
    
    // prepare user object
    $user = new User($db);
    
    // get id of user to be edited
    $data = json_decode(file_get_contents("php://input"));
    
    // set ID property of user to be edited
    $user->userID = $data->userID;
    
    // set user property values
    $user->username = $data->username;
    $user->name = $data->name;
    $user->cardNo = $data->cardNo;
    $user->bankName = $data->bankName;
    $user->address = $data->address;
    
    // update the user
    if($user->update()){
    
        // set response code - 200 ok
        http_response_code(200);
    
        // tell the user
        echo json_encode(array("message" => "The user info was updated."));
    }
    
    // if unable to update the user info, tell the user
    else{
    
        // set response code - 503 service unavailable
        http_response_code(503);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update the user info."));
    }

?>