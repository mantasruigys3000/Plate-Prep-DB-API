<?php

require "./DB.php";
$db = new DB();

$postedContent = json_decode(file_get_contents("php://input"));
//$postedContent->id = '1';
//$postedContent->name = 'memeballs';
echo json_encode($postedContent);



echo $db->insertMeal($postedContent->id,$postedContent->name);


?>