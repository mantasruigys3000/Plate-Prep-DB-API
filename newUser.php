<?php

require "./DB.php";
$db = new DB();

$postedContent = json_decode(file_get_contents("php://input"));


$db->insertUser(
    $postedContent->fname,
    $postedContent->sname,
    $postedContent->email,
    $postedContent->password,
    (int)$postedContent->isPrem

);






?>