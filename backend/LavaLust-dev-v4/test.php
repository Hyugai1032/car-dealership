<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'johnrheynedamotamares2005@gmail.com';
    $mail->Password = 'isebrtolhpyifuhh';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('johnrheynedamotamares2005@gmail.com', 'Mailer Test');
    $mail->addAddress('jhonrheytamares@gmail.com');
    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body = 'This is a test email from PHPMailer.';

    $mail->send();
    echo 'Email sent successfully';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}
