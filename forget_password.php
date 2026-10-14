<?php
session_start();
include("includes/db.php");

if(isset($_POST['reset']))
{
    $email = mysqli_real_escape_string($conn,$_POST['email']);

    $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($check)>0)
    {
        $newPassword = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"),0,8);

        $hash = password_hash($newPassword,PASSWORD_DEFAULT);

        mysqli_query($conn,"
        UPDATE users
        SET password='$hash'
        WHERE email='$email'
        ");

        require("send_otp.php");

        if(sendPassword($email,$newPassword))
        {
            echo "<script>
            alert('New password sent to your email');
            window.location='login.php';
            </script>";
        }
        else
        {
            echo "<script>alert('Unable to send email');</script>";
        }
    }
    else
    {
        echo "<script>alert('Email not found');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Forgot Password</title>

<link rel="stylesheet" href="css/style.css">

</head>

<body>

<?php include("includes/header.php"); ?>

<div class="container">

<div class="card shadow p-4" style="max-width:500px;margin:auto;margin-top:50px;">

<h2 align="center">Forgot Password</h2>

<form method="POST">

<label>Email Address</label>

<input
type="email"
name="email"
class="form-control"
required>

<br>

<input
type="submit"
name="reset"
value="Reset Password"
class="btn btn-primary">

</form>

</div>

</div>

<?php include("includes/footer.php"); ?>

</body>
</html>