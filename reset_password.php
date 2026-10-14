<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['reset_email']))
{
    header("Location: forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];

if(isset($_POST['reset']))
{
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if($password != $confirm)
    {
        echo "<script>alert('Passwords do not match');</script>";
    }
    else
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        mysqli_query($conn,"
        UPDATE users
        SET password='$hash'
        WHERE email='$email'
        ");

        mysqli_query($conn,"
        DELETE FROM password_resets
        WHERE email='$email'
        ");

        unset($_SESSION['reset_email']);

        echo "<script>
        alert('Password Reset Successfully');
        window.location='login.php';
        </script>";
    }
}
?>

<!DOCTYPE html>

<html>

<head>

<title>Reset Password</title>

<link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="container">

<h2 align="center">Reset Password</h2>

<form method="POST">

<label>New Password</label>

<input
type="password"
name="password"
required>

<br><br>

<label>Confirm Password</label>

<input
type="password"
name="confirm_password"
required>

<br><br>

<input
type="submit"
name="reset"
value="Reset Password"
class="btn">

</form>

</div>

</body>

</html>