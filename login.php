<?php
require "./DB.php";
$db = new DB();

$postedContent = json_decode(file_get_contents("php://input"));
$response = $db->login($postedContent->email,$postedContent->password);
//$response = $db->login('e','p');
$out = [];
$out["result"] = $response["result"];

if($response["result"] == "true"){
    
    $out["name"] = $response["account"]["account_fname"];
    $out["id"] = $response["account"]["account_id"];
    
    

}
echo json_encode($out);

?>