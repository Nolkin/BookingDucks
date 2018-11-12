<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate order object
include_once '../objects/orders.php';
 
$database = new Database();
$db = $database->getConnection();
 
$order = new Order($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty

if(
    !empty($data->owner) 
   
){
 
    // set order property values
    $order->owner = $data->owner;
    $order->created = date('Y-m-d H:i:s');
    $reason="";
    // create the order
    if($order->create($reason)){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("message" => "Order was created.","id"=>$order->id));
    }
 
    // if unable to create the order, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
        
        // tell the user
        echo json_encode(array("message" => "Error: ".$reason)); //"Unable to create order."));
        //var_dump($reason) ;
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create order. Data is incomplete."));
}
?>