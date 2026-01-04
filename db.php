<?php
/*
$host = "localhost"; 
$user = "tauhidsh_oquizuser"; // change to your remote DB user  
$pass = "OquizTSS@";          // change to your remote DB password
$db   = "tauhidsh_oquiz";     // change to your remote DB name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("DB Connection Failed: " . $conn->connect_error);
}
*/

$host = "localhost";   // XAMPP default
$user = "root";        // XAMPP default
$pass = "";            // XAMPP default (empty)
$db   = "oquiz_db";    // change to your local DB name

$conn = new mysqli($host, $user, $pass, $db); 
if ($conn->connect_error)   
    {
    die("DB Connection Failed: " . $conn->connect_error);
}
?>