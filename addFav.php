<?php
require "./DB.php";
$db = new DB();
    
$postedContent = json_decode(file_get_contents("php://input"));

$account_id = (int)$postedContent->account_id;
$meal_id = (int)$postedContent->meal_id;
$db->addFav($account_id,$meal_id);


?>