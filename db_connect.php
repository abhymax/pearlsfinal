<?php
$servername = "localhost";
$username = "pe3a2rls1sh5in_mahavir";
$password = "L5j!XVvY7oRhDc1J";
$dbname = "pe3a2rls1sh5in_shiv";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");