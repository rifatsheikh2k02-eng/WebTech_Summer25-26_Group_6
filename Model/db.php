<?php

$server = "localhost";
$username = "root";
$password = "";
$database = "boighor";

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Database Connection Failed");
}

?>