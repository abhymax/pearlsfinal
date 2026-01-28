<?php
// FILE: api.php
header('Content-Type: application/json');

// =======================================================================
// 1. SAFETY NET (Debug Mode)
// This catches "Invisible" crashes (like missing files) and shows them in the popup
// =======================================================================
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && ($error['type'] === E_ERROR || $error['type'] === E_PARSE || $error['type'] === E_COMPILE_ERROR)) {
        // If the script crashed, tell the user WHY
        echo json_encode(["status" => "error", "message" => "Critical Server Error: " . $error['message'] . " on line " . $error['line']]);
        die();
    }
});
// Turn off standard error printing so it doesn't break the JSON response
ini_set('display_errors', 0);
error_reporting(E_ALL);


// =======================================================================
// 2. CHECK FILES BEFORE LOADING
// =======================================================================
if (!file_exists('config.php')) {
    echo json_encode(["status" => "error", "message" => "Missing File: config.php"]); exit;
}
require 'config.php';

// Check for PHPMailer files
if (!file_exists('phpmailer/PHPMailer.php')) {
    echo json_encode(["status" => "error", "message" => "Missing Folder: 'phpmailer' folder not found. Please upload it."]); exit;
}

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// =======================================================================
// 3. YOUR GMAIL SETTINGS (Already Filled)
// =======================================================================
$smtp_user = 'shivpandey94@gmail.com'; 
$smtp_pass = 'xkoj jnvv yych mzzm';   // Your App Password
$smtp_host = 'smtp.gmail.com';
$smtp_port = 465;
$smtp_sec  = PHPMailer::ENCRYPTION_SMTPS;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Sanitize Inputs
    $name    = htmlspecialchars(strip_tags(trim($_POST['name'] ?? '')));
    $phone   = htmlspecialchars(strip_tags(trim($_POST['phone'] ?? '')));
    $email   = htmlspecialchars(strip_tags(trim($_POST['email'] ?? '')));
    $date    = htmlspecialchars(strip_tags(trim($_POST['date'] ?? '')));
    $service = htmlspecialchars(strip_tags(trim($_POST['service'] ?? '')));
    $reason  = htmlspecialchars(strip_tags(trim($_POST['reason'] ?? '')));
    $source  = htmlspecialchars(strip_tags(trim($_POST['source'] ?? 'General')));

    if(empty($name) || empty($phone)) {
        echo json_encode(["status" => "error", "message" => "Name and Phone are required."]);
        exit;
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO appointments (patient_name, phone, email, preferred_date, service_type, reason, booking_source) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $phone, $email, $date, $service, $reason, $source);

    if ($stmt->execute()) {
        
        // Setup Email
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $smtp_host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtp_user;
            $mail->Password   = $smtp_pass;
            $mail->SMTPSecure = $smtp_sec;
            $mail->Port       = $smtp_port;

            // --- SEND TO DOCTOR ---
            $mail->setFrom($smtp_user, 'Website Booking'); 
            $mail->addAddress($smtp_user); 
            if(!empty($email)) { $mail->addReplyTo($email, $name); }

            $mail->isHTML(true);
            $mail->Subject = "🔔 New Booking: $name ($date)";
            $mail->Body = "
                <h3>New Appointment Request</h3>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Phone:</strong> <a href='tel:$phone'>$phone</a></p>
                <p><strong>Date:</strong> $date</p>
                <p><strong>Service:</strong> $service</p>
                <p><strong>Reason:</strong> $reason</p>
                <p><strong>Source:</strong> $source</p>
            ";
            $mail->send();

            // --- SEND TO PATIENT ---
            if (!empty($email)) {
                $mail->clearAddresses(); 
                $mail->clearReplyTos();
                $mail->setFrom($smtp_user, 'Pearls Shine Dental');
                $mail->addAddress($email); 
                $mail->Subject = "✅ Appointment Received";
                $mail->Body = "
                    <div style='font-family: sans-serif;'>
                        <h2 style='color:#2563eb'>Pearls Shine Dental</h2>
                        <p>Hello $name,</p>
                        <p>We received your request for <strong>$service</strong> on <strong>$date</strong>.</p>
                        <p>We will call you at <strong>$phone</strong> shortly to confirm.</p>
                    </div>
                ";
                $mail->send();
            }

            echo json_encode(["status" => "success", "message" => "Booking Confirmed! Check your email."]);

        } catch (Exception $e) {
            // If email fails, STILL tell the user success (because DB saved it), but warn them.
            echo json_encode(["status" => "success", "message" => "Booking Saved, but Email Failed: " . $mail->ErrorInfo]);
        }
        
    } else {
        echo json_encode(["status" => "error", "message" => "Database Error: " . $conn->error]);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
}
?>