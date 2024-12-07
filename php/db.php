<?php

$db_host = "localhost";
$db_username = "root";
$db_password = "";
$db_database = "mytext";

$db = new mysqli(
    $db_host, 
    $db_username,
    $db_password,
    $db_database
);

if($db->connect_error){
    echo "Error: " . $db->connect_error;
}

