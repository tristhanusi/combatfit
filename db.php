<?php
$host = "sql108.infinityfree.com"; 
$user = "if0_42284076";           
$pass = "bad0123boys";   
$dbname = "if0_42284076_combatfit_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>