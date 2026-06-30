<?php

function required($value)
{
    return trim($value) != "";
}

function validEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validNumber($number)
{
    return is_numeric($number);
}

function validPassword($password)
{
    return strlen($password) >= 6;
}

function validImage($file)
{
    $allow = [
        "image/jpeg",
        "image/png",
        "image/webp"
    ];

    return in_array($file["type"], $allow);
}