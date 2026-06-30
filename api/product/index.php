<?php

require_once "../config/database.php";
require_once "../config/response.php";

$sql="SELECT
id,
name,
price,
description,
image
FROM products
ORDER BY id DESC";

$result=$conn->query($sql);

$data=[];

while($row=$result->fetch_assoc())
{
    $row['image']="http://localhost/shop/uploads/".$row['image'];

    $data[]=$row;
}

success($data,"Load products successfully");