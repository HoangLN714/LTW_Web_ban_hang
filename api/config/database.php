<?php

header("Content-Type: application/json; charset=UTF-8");

$host="localhost";
$user="root";
$password="";
$database="web_ban_hang";

$conn=new mysqli($host,$user,$password,$database);

if($conn->connect_error){
    http_response_code(500);

    echo json_encode([
        "success"=>false,
        "message"=>"Database connection failed"
    ]);

    exit();
}

$conn->set_charset("utf8mb4");