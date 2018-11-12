<?php
// required header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/ducks.php';
 
// instantiate database and duck object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$duck = new Ducks($db);
 
// query ducks
$stmt = $duck->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // ducks array
    $ducks_arr=array();
    $ducks_arr["records"]=array();
 
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $duck_item=array(
            "id" => $id,
            "price" => $price,
            "color" => html_entity_decode($color),
            "owner" => html_entity_decode($owner)
        );
 
        array_push($ducks_arr["records"], $duck_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show ducks data in json format
    echo json_encode($ducks_arr);
}
 
else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no ducks found
    echo json_encode(
        array("message" => "No ducks found.")
    );
}
?>