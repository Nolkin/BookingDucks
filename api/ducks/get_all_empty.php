<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/ducks.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare duck object
$duck = new Ducks($db);
 
 
// query for all empty ducks
$stmt = $duck->getAllEmpty();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // ducks array
    $ducks_arr=array();
    $ducks_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row to variables
        extract($row);
 
        $duck_item=array(
            "color" => html_entity_decode($color),
            "count" => $sum,
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
 
    // tell the user duck does not exist
    echo json_encode(array("message" => "Duck does not exist."));
}
?>