<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Check if required fields are empty or if the email is invalid
if (empty($_POST['name']) || empty($_POST['subject']) || empty($_POST['message']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400); // Use 400 for bad request
    exit('Invalid input');
}

// Sanitize input data
$name = htmlspecialchars(strip_tags($_POST['name']));
$email = htmlspecialchars(strip_tags($_POST['email']));
$m_subject = htmlspecialchars(strip_tags($_POST['subject']));
$message = htmlspecialchars(strip_tags($_POST['message']));

// Define recipient email
$to = "slavitransbg@gmail.com"; // Change this email to your desired recipient

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; 
    $mail->isSMTP();
    $mail->Host = 'sandbox.smtp.mailtrap.io'; // Mailtrap SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'ba9a54b39a4b75'; // Mailtrap username
    $mail->Password = '289bcc3fd1103d'; // Mailtrap password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 2525;

    // Recipients
    $mail->setFrom($email, $name);
    $mail->addAddress($to); // Add a recipient
    $mail->addReplyTo($email, $name);

    // Content
    $mail->isHTML(false);
    $mail->Subject = "$m_subject: $name";
    $mail->Body = "You have received a new message from your website contact form.\n\n" .
                  "Here are the details:\n\n" .
                  "Name: $name\n" .
                  "Email: $email\n" .
                  "Subject: $m_subject\n" .
                  "Message: $message\n";

    // Send the email
    $mail->send();
    http_response_code(200);
    echo 'Message sent successfully';
} catch (Exception $e) {
    http_response_code(500); // Internal server error
    exit('Failed to send email. Mailer Error: ' . $mail->ErrorInfo);
}

