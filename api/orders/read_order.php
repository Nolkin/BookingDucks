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
 
// set ID property of record to read
$order->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// the details of order
$count_price = $order->countPrice();
$sum_price= $order->countSum();
$order->getOrderInfo();

if($count_price>0){
         
    // set response code - 200 OK
    http_response_code(200);
    $arr=[];
   
   foreach($order->ducks as $key => $value) 
  { 
     $item=array(
            "color" => $key,
            "num" => $value
     );
     array_push($arr, $item);
  }
  
    // make it json format
    echo json_encode(array("id"=>$order->id,"CountPrice" => $count_price,"SumPrice" => $sum_price,"Ducks"=>$arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user order does not exist
    echo json_encode(array("message" => "Order does not exist."));
}
?>