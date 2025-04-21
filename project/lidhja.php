<?php

$host = "localhost";
$database_name = "web_database";
$username = "root";
$password = "";
$port = 3306;

$connection= new mysqli($host , $username , $password, $database_name , $port);

if ($connection->connect_error) {
    die("Connection failed: " .$connection->connect_error);
}


echo "";


?>