<?php

$host = "localhost";
$user = "root";
$password = "";
$nama_database = "managementsystem";

$db = mysqli_connect($host, $user, $password, $nama_database);

if( !$db ){
    die("Failed to connect to database " . mysqli_connect_error());
}
?>