<?php
session_start();

include("includes/db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if(isset($_POST['change']))
{
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $user = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT *
    FROM users
    WHERE id='$user_id'
    "));

    if(!password_verify($old_password,$user['password']))
    {
        echo "<script>alert('Old Password is Incorrect');</script>";
    }
    elseif(strlen($new_password) < 6)
    {
        echo "<script>alert('New Password must contain at least 6 characters');</script>";
    }
    elseif($new_password != $confirm_password)
    {
        echo "<script>alert('New Password and Confirm Password do not match');</script>";
    }
    else
    {
        $new_hash = password_hash($new_password,PASSWORD_DEFAULT);

        mysqli_query($conn,"
        UPDATE users
        SET password='$new_hash'
        WHERE id='$user_id'
        ");

        echo "<script>
        alert('Password Changed Successfully');
        window.location='profile.php';
        </script>";
        exit();
    }
}
?>

<?php include("includes/header.php"); ?>

<div class="container mt-5 mb-5">

<div class="card shadow" style="max-width:600px;margin:auto;">

<div class="card-header bg-primary text-white">

<h3 class="text-center">

🔒 Change Password

</h3>

</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label>Old Password</label>

<input
type="password"
name="old_password"
class="form-control"
required>

</div>

<div class="mb-3">

<label>New Password</label>

<input
type="password"
name="new_password"
class="form-control"
required>

<small class="text-muted">

Minimum 6 characters

</small>

</div>

<div class="mb-3">

<label>Confirm Password</label>

<input
type="password"
name="confirm_password"
class="form-control"
required>

</div>

<div class="text-center">

<button
type="submit"
name="change"
class="btn btn-success btn-lg">

Update Password

</button>

<a
href="profile.php"
class="btn btn-secondary btn-lg">

Cancel

</a>

</div>

</form>

</div>

</div>

</div>

<?php include("includes/footer.php"); ?>