<?php
session_start();
include("includes/db.php");

if(isset($_POST['verify']))
{
    $otp=$_POST['otp'];
    $email=$_SESSION['reset_email'];

    $check=mysqli_query($conn,"
    SELECT *
    FROM password_resets
    WHERE email='$email'
    AND otp='$otp'
    ");

    if(mysqli_num_rows($check)>0)
    {
        header("Location: reset_password.php");
        exit();
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

<title>Verify OTP</title>

<link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="container">

<h2 align="center">Verify OTP</h2>

<form method="POST">

<input
type="text"
name="otp"
placeholder="Enter OTP"
required>

<br><br>

<input
type="submit"
name="verify"
value="Verify OTP"
class="btn">

</form>

</div>

</body>

</html>