<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
session_start();

$email = $_POST['email'];
$userType = $_POST['userType'];
$code = rand(100000, 999999);

// Save in session
$_SESSION['reset_code'] = $code;
$_SESSION['reset_email'] = $email;
$_SESSION['user_type'] = $userType;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'mrakri15@gmail.com';       // ✅ Your Gmail address
    $mail->Password   = 'ytoy uqld bdky xaud';      // ✅ App password only (not your real Gmail password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('mrakri15@gmail.com', 'Task');
    $mail->addAddress($email); 

    $mail->isHTML(true);
    $mail->Subject = 'Your Password Reset Code';
    $mail->Body    = "Your password reset code is <b>$code</b>";

    $mail->send();
    echo "Code sent to your email.";
} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
}
?>
