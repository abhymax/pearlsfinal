<?php
// FILE: api.php
header('Content-Type: application/json');
require 'db_connect.php';

$doctor_email = "shivpandey94@gmail.com"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Sanitize Inputs
    $name   = htmlspecialchars(strip_tags(trim($_POST['name'] ?? '')));
    $phone  = htmlspecialchars(strip_tags(trim($_POST['phone'] ?? '')));
    $email  = htmlspecialchars(strip_tags(trim($_POST['email'] ?? ''))); // New
    $date   = htmlspecialchars(strip_tags(trim($_POST['date'] ?? '')));
    $service= htmlspecialchars(strip_tags(trim($_POST['service'] ?? '')));
    $reason = htmlspecialchars(strip_tags(trim($_POST['reason'] ?? ''))); // New
    $source = htmlspecialchars(strip_tags(trim($_POST['source'] ?? 'General'))); // New

    // 2. Validation
    if(empty($name) || empty($phone)) {
        echo json_encode(["status" => "error", "message" => "Name and Phone are required."]);
        exit;
    }

    // 3. Insert into Database (Updated Query)
    $stmt = $conn->prepare("INSERT INTO appointments (patient_name, phone, email, preferred_date, service_type, reason, booking_source) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $phone, $email, $date, $service, $reason, $source);

    if ($stmt->execute()) {
        // Email Notification
        $subject = "New Booking ($source): $name";
        $msg = "New Appointment:\n\nName: $name\nPhone: $phone\nEmail: $email\nDate: $date\nService: $service\nSource: $source\nReason: $reason";
        $headers = "From: no-reply@pearlsshine.co.in";
        @mail($doctor_email, $subject, $msg, $headers);

        echo json_encode(["status" => "success", "message" => "Booking Confirmed!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Error."]);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
}
?>