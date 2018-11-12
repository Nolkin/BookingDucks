<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/orders.php';

 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare order object
$order = new Order($db);

// set color and owner id property of record to remove
$order->ownerId = isset($_GET['id']) ? $_GET['id'] : die();
$duck_color= isset($_GET['color']) ? $_GET['color'] : die();


$res = $order->removeDuck($duck_color);

if($res==-1){
         
     // set response code - 404 Not found
    http_response_code(200);
 
    // tell the user Duck does not exist
    echo json_encode(array("message" => "Duck finding error"));
}
 

if($res==1) {
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode(array("message" => "Ok"));
}
else {
    // set response code - 404 Not found
    http_response_code(200);
 
    // tell the user 
    echo json_encode(array("message" => "DB error","res" => $res));
}
?>