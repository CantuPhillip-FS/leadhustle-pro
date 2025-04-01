<?php
// send_registration_emails.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Require PHPMailer classes. Adjust the path as needed.
require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

/**
 * Sends confirmation emails upon successful user registration.
 *
 * @param string $userEmail    The registered user's email address.
 * @param string $userPassword The registered user's password.
 * @param string $userName     The registered user's first name.
 *
 * @return void
 */
function sendRegistrationEmails($userEmail, $userPassword, $userName) {
    // SMTP Configuration
    $smtpHost = 'leadhustle.pro';
    $smtpAuth = true;
    $smtpUsername = 'no-reply@leadhustle.pro';
    $smtpPassword = 'LeadHustle.Pro2024$$';
    $smtpSecure = 'ssl';
    $smtpPort = 465;

    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = $smtpHost;
        $mail->SMTPAuth   = $smtpAuth;
        $mail->Username   = $smtpUsername;
        $mail->Password   = $smtpPassword;
        $mail->SMTPSecure = $smtpSecure;
        $mail->Port       = $smtpPort;

        // Common settings
        $mail->setFrom('no-reply@leadhustle.pro', 'LeadHustle.pro');

        // ---------------------------
        // 1. Send Confirmation Email to User
        // ---------------------------
        $userMail = clone $mail; // Clone the PHPMailer instance for multiple emails

        // Recipient
        $userMail->addAddress($userEmail, $userName);

        // Content
        $userMail->isHTML(true);
        $userMail->Subject = 'Welcome to LeadHustle.pro! Your Account Details Inside';
        $userMail->Body    = getUserConfirmationEmailBody($userEmail, $userPassword, $userName);
        $userMail->AltBody = getUserConfirmationEmailAltBody($userEmail, $userPassword, $userName);

        $userMail->send();
        // echo 'Confirmation email has been sent to the user.';
        
        // ---------------------------
        // 2. Send Notification Email to Support
        // ---------------------------
        $supportMail = clone $mail;

        // Recipient
        $supportMail->addAddress('support@leadhustle.pro', 'Support Team');

        // Content
        $supportMail->isHTML(true);
        $supportMail->Subject = 'New User Registration: ' . $userEmail;
        $supportMail->Body    = getSupportNotificationEmailBody($userEmail);
        $supportMail->AltBody = getSupportNotificationEmailAltBody($userEmail);

        $supportMail->send();
        // echo 'Notification email has been sent to support.';
    } catch (Exception $e) {
        // Handle exceptions (you can log errors here)
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}

/**
 * Generates the HTML body for the user's confirmation email.
 *
 * @param string $email    User's email address.
 * @param string $password User's password.
 *
 * @return string HTML content.
 */
function getUserConfirmationEmailBody($email, $password) {
    // You can customize the email content as needed
    return "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Welcome to LeadHustle.pro!</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f9;
                color: #333;
                padding: 20px;
            }
            .container {
                background-color: #ffffff;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                max-width: 600px;
                margin: auto;
            }
            .header {
                text-align: center;
                padding-bottom: 20px;
            }
            .header img {
                width: 150px;
            }
            .content {
                line-height: 1.6;
            }
            .button {
                display: inline-block;
                padding: 12px 25px;
                margin-top: 20px;
                background-color: #1abc9c;
                color: #ffffff;
                text-decoration: none;
                border-radius: 5px;
            }
            .footer {
                text-align: center;
                margin-top: 30px;
                font-size: 0.9em;
                color: #777777;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <img src='https://leadhustle.pro/images/logo.png' alt='LeadHustle.pro Logo'>
            </div>
            <div class='content'>
                <h2>Welcome to LeadHustle.pro!</h2>
                <p>Thank you for creating an account with us. Below are your account details:</p>
                <p><strong>Email Address:</strong> {$email}</p>
                <p><strong>Password:</strong> {$password}</p>
                <p>Please save this information in a secure place for your records.</p>
                <p><strong>Important:</strong> Do not reply to this email as our inbox is not monitored.</p>
                <p>If you have any questions or need assistance, feel free to reach out to our support team at <a href='mailto:support@leadhustle.pro'>support@leadhustle.pro</a>.</p>
                <a href='https://leadhustle.pro/login.php' class='button'>Log Into Your Account</a>
            </div>
            <div class='footer'>
                &copy; " . date('Y') . " LeadHustle.pro. All rights reserved.
            </div>
        </div>
    </body>
    </html>
    ";
}

/**
 * Generates the plain text body for the user's confirmation email.
 *
 * @param string $email    User's email address.
 * @param string $password User's password.
 *
 * @return string Plain text content.
 */
function getUserConfirmationEmailAltBody($email, $password) {
    return "
Welcome to LeadHustle.pro!

Thank you for creating an account with us. Below are your account details:

Email Address: {$email}
Password: {$password}

Please save this information in a secure place for your records.

Important: Do not reply to this email as our inbox is not monitored.

If you have any questions or need assistance, feel free to reach out to our support team at support@leadhustle.pro.

Log Into Your Account: https://leadhustle.pro/login.php

© " . date('Y') . " LeadHustle.pro. All rights reserved.
";
}

/**
 * Generates the HTML body for the support notification email.
 *
 * @param string $userEmail User's email address.
 *
 * @return string HTML content.
 */
function getSupportNotificationEmailBody($userEmail) {
    $registrationDate = date('l, F j, Y, g:i a');

    return "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>New User Registration</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f9;
                color: #333;
                padding: 20px;
            }
            .container {
                background-color: #ffffff;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                max-width: 600px;
                margin: auto;
            }
            .header {
                text-align: center;
                padding-bottom: 20px;
            }
            .header img {
                width: 150px;
            }
            .content {
                line-height: 1.6;
            }
            .footer {
                text-align: center;
                margin-top: 30px;
                font-size: 0.9em;
                color: #777777;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <img src='https://leadhustle.pro/images/logo.png' alt='LeadHustle.pro Logo'>
            </div>
            <div class='content'>
                <h2>New User Registration</h2>
                <p>A new user has registered on LeadHustle.pro.</p>
                <p><strong>Email Address:</strong> {$userEmail}</p>
                <p><strong>Registration Date:</strong> {$registrationDate}</p>
            </div>
            <div class='footer'>
                &copy; " . date('Y') . " LeadHustle.pro. All rights reserved.
            </div>
        </div>
    </body>
    </html>
    ";
}

/**
 * Generates the plain text body for the support notification email.
 *
 * @param string $userEmail User's email address.
 *
 * @return string Plain text content.
 */
function getSupportNotificationEmailAltBody($userEmail) {
    $registrationDate = date('l, F j, Y, g:i a');

    return "
New User Registration

A new user has registered on LeadHustle.pro.

Email Address: {$userEmail}
Registration Date: {$registrationDate}

© " . date('Y') . " LeadHustle.pro. All rights reserved.
";
}
?>