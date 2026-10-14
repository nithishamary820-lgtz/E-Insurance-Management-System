<?php
session_start();
include("includes/db.php");

if(isset($_POST['verify']))
{
    $otp = $_POST['otp'];
    $email = $_SESSION['verify_email'];

    $check = mysqli_query($conn,"
    SELECT *
    FROM email_verification
    WHERE email='$email'
    AND otp='$otp'
    ");

    if(mysqli_num_rows($check)>0)
    {
        mysqli_query($conn,"
        UPDATE users
        SET email_verified='Yes'
        WHERE email='$email'
        ");

        mysqli_query($conn,"
        DELETE FROM email_verification
        WHERE email='$email'
        ");

        unset($_SESSION['verify_email']);

        echo "<script>
        alert('Email Verified Successfully');
        window.location='login.php';
        </script>";
    }
    else
    {
        echo "<script>alert('Invalid OTP');</script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Verify Email</title>

<link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="container">

<h2 align="center">Email Verification</h2>

<form method="POST">

<label>Enter OTP</label>

<input
type="text"
name="otp"
required>

<br><br>

<input
type="submit"
name="verify"
value="Verify"
class="btn">

</form>

</div>

</body>

</html>