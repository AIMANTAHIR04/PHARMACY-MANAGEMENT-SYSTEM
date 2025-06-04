<?php
/* Database credentials */
$host = "localhost";
$user = "root";      // Default XAMPP username
$password = "";      // Default XAMPP password (blank)
$database = "pharmacy"; // Your database name

/* Attempt to connect to MySQL database */
$link = mysqli_connect($host, $user, $password, $database);

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>