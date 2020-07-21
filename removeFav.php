<?php
require "./DB.php";
$db = new DB();
    
$postedContent = json_decode(file_get_contents("php://input"));

$db->removeFav($postedContent->account_id,$postedContent->meal_id);
//$db->removeFav(1,1);

?>