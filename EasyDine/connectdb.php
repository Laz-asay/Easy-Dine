<?php

//establish connection to database
$dbServerName = "localhost";
$username = "root";
$password = "";
$dbName= "food4thought";

$conn = new mysqli($dbServerName, $username, $password, $dbName);

if ($conn -> connect_error)
{
    die ("Connection error: ". $conn->connect_error);
}