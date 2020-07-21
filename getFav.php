<?php
require "./DB.php";
$db = new DB();
    
$postedContent = json_decode(file_get_contents("php://input"));

echo $db->getFavs(1);

?>