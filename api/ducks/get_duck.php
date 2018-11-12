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
 
// initialize object
$duck = new Ducks($db);
 
// set ID property of record to read
$duck->owner = isset($_GET['owner']) ? $_GET['owner'] : die();
$duck->color = isset($_GET['color']) ? $_GET['color'] : die();
// read the details of duck to be edited
$duck->getDuck();
 
if($duck->id!=null){
    // create array
    $duck_arr = array(
        "id" =>  $duck->id,
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($duck_arr);
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user Duck does not exist
    echo json_encode(array("message" => "Duck does not exist."));
}
?>