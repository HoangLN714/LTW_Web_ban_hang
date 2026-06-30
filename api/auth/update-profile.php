<?php

require_once "../config/auth.php";
require_once "../config/database.php";
require_once "../config/response.php";

$fullname=$_POST['fullname'];
$email=$_POST['email'];

$id=$_SESSION['user_id'];

$stmt=$conn->prepare("
UPDATE users
SET
fullname=?,
email=?
WHERE id=?
");

$stmt->bind_param(
"ssi",
$fullname,
$email,
$id
);

if($stmt->execute()){

    success([],"Profile updated");

}

error("Update failed");