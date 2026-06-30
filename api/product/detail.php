<?php

require_once "../config/database.php";
require_once "../config/response.php";

if (!isset($_GET['id'])) {
    error("Missing product id");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("
    SELECT *
    FROM products
    WHERE id=?
");

$stmt->bind_param("i",$id);
$stmt->execute();

$result=$stmt->get_result();

if($result->num_rows==0){
    error("Product not found",404);
}

$product=$result->fetch_assoc();

$product['image']="http://localhost/shop/uploads/".$product['image'];

success($product);