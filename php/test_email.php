<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Require PHPMailer classes. Adjust the path as needed.
require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

// Initialize PHPMailer
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'leadhustle.pro';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'no-reply@leadhustle.pro';
    $mail->Password   = 'LeadHustle.Pro2024$$'; // Replace with actual password
    $mail->SMTPSecure = 'ssl'; // or 'tls' if required
    $mail->Port       = 465; // or 587 for TLS

    // Recipients
    $mail->setFrom('no-reply@leadhustle.pro', 'LeadHustle.pro');
    $mail->addAddress('thereisphil@gmail.com', 'Phillip'); // Replace with your email for testing

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from LeadHustle.pro';
    $mail->Body    = '<h1>This is a Test Email</h1><p>If you see this, PHPMailer is working correctly.</p>';
    $mail->AltBody = 'This is a Test Email. If you see this, PHPMailer is working correctly.';

    $mail->send();
    echo 'Test email has been sent successfully.';
} catch (Exception $e) {
    echo "Test email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>