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
include_once '../objects/ducks.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare order object
$order = new Order($db);



// set color and id property of record to add
$order->id = isset($_GET['id']) ? $_GET['id'] : die();
$duck_color= isset($_GET['color']) ? $_GET['color'] : die();
// read the details of order to be edited
$res = $order->addDuck($duck_color);


if($res==-1){
         
     // set response code - 404 Not found
    http_response_code(200);
 
    // tell the user Duck does not match color
    echo json_encode(array("message" => "Duck does not match color."));
}
 
else if($res==-2){
    // set response code - 404 Not found
    http_response_code(200);
 
    // tell the user Duck does not exist
    echo json_encode(array("message" => "Duck does not exist."));
}
else {
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode(array("message" => "Ok","id" =>$res));
}
?>