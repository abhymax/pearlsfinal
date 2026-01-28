<?php
// Load credentials securely
require_once __DIR__ . '/db_credentials.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    // Don't echo the exact error to the public (security risk)
    error_log("Connection failed: " . $conn->connect_error);
    die("Database Connection Error. Please try again later.");
}
$conn->set_charset("utf8mb4");
?>