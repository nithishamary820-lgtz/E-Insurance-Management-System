<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


/* =====================================================
   SEND OTP - Used for Registration Email Verification
   ===================================================== */

function sendOTP($email, $otp)
{
    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'nithishacompscicasc@gmail.com';
        $mail->Password = 'exhw vtdp ywra eqak';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom(
            'nithishacompscicasc@gmail.com',
            'Insurance Management'
        );

        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Email Verification OTP';

        $mail->Body = "
            <h2>Your OTP is</h2>
            <h1>$otp</h1>
            <p>OTP expires in 5 minutes.</p>
        ";

        $mail->send();

        return true;

    } catch (Exception $e) {

        return false;
    }
}


/* =====================================================
   SEND NEW PASSWORD - Used for Forgot Password
   ===================================================== */

function sendPassword($email, $newPassword)
{
    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'nithishamary820@gmail.com';
        $mail->Password = 'drgm yzpz rzom zgkr';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom(
            'nithishamary820@gmail.com',
            'Insurance Management'
        );

        $mail->addAddress($email);

        $mail->isHTML(true);

        $mail->Subject = 'Insurance Management - New Password';

        $mail->Body = "
            <h2>Password Reset Successful</h2>

            <p>Your password has been reset successfully.</p>

            <p>Your new password is:</p>

            <h2>$newPassword</h2>

            <p>Please login using this password.</p>

            <p>For security, please change your password after login.</p>

            <br>

            <p>Regards,<br>
            Insurance Management Team</p>
        ";

        $mail->send();

        return true;

    } catch (Exception $e) {

        return false;
    }
}

?>