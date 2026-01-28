<?php
// FILE: api.php
header('Content-Type: application/json');

// 1. Load Config & PHPMailer
require 'config.php';

// Adjust these paths if your 'phpmailer' folder is in a different location
require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// --- GMAIL SMTP SETTINGS ---
// 1. The email account that will SEND the emails (The Doctor's Gmail)
$smtp_user = 'shivpandey94@gmail.com'; 

// 2. The Google App Password you generated (NOT the login password)
// It looks like: abcd efgh ijkl mnop
$smtp_pass = 'xkoj jnvv yych mzzm'; 

// 3. Gmail Server Settings
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

            // --- 1. EMAIL TO DOCTOR ---
            // Gmail forces the sender to be your account ($smtp_user)
            $mail->setFrom($smtp_user, 'Website Booking System'); 
            $mail->addAddress($smtp_user);     // Send TO the doctor
            
            // If the patient provided an email, let the doctor reply to them directly
            if(!empty($email)) {
                $mail->addReplyTo($email, $name);
            }

            $mail->isHTML(true);
            $mail->Subject = "🔔 New Booking: $name ($date)";
            
            $mail->Body = "
            <h2>New Appointment Request</h2>
            <p><strong>Source:</strong> $source</p>
            <table cellpadding='5' border='1' style='border-collapse:collapse; border-color: #ddd;'>
                <tr><td><strong>Name:</strong></td><td>$name</td></tr>
                <tr><td><strong>Phone:</strong></td><td><a href='tel:$phone'>$phone</a></td></tr>
                <tr><td><strong>Email:</strong></td><td>$email</td></tr>
                <tr><td><strong>Date:</strong></td><td>$date</td></tr>
                <tr><td><strong>Service:</strong></td><td>$service</td></tr>
                <tr><td><strong>Reason:</strong></td><td>$reason</td></tr>
            </table>";
            
            $mail->send();

            // --- 2. EMAIL TO PATIENT (Confirmation) ---
            if (!empty($email)) {
                $mail->clearAddresses(); 
                $mail->clearReplyTos();
                
                // Send FROM the doctor TO the patient
                $mail->setFrom($smtp_user, 'Pearls Shine Dental');
                $mail->addAddress($email); 
                
                $mail->Subject = "✅ We received your appointment request";
                
                $mail->Body = "
                <div style='font-family: Arial, sans-serif; color: #333;'>
                    <h2 style='color: #2563eb;'>Pearls Shine Dental</h2>
                    <p>Hello <strong>$name</strong>,</p>
                    <p>We have received your request for an appointment on <strong>$date</strong> for <strong>$service</strong>.</p>
                    <p>Our team will review the schedule and call you at <strong>$phone</strong> shortly to confirm.</p>
                    <br>
                    <p>Need urgent help? Call us: <a href='tel:".val('phone')."'>".val('phone')."</a></p>
                </div>";
                
                $mail->send();
            }

            echo json_encode(["status" => "success", "message" => "Booking Confirmed!"]);

        } catch (Exception $e) {
            // Log the error to a file on your server for debugging
            error_log("Mailer Error: {$mail->ErrorInfo}");
            // Still tell the user success because the database part worked
            echo json_encode(["status" => "success", "message" => "Booking saved (Email notification pending)."]);
        }
        
    } else {
        echo json_encode(["status" => "error", "message" => "Database Error."]);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
}
?>