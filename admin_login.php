<?php

session_start();

include("../includes/db.php");

if(isset($_SESSION['admin']))
{
    header("Location: dashboard.php");
    exit();
}

$error="";

if(isset($_POST['login']))
{

    $username=mysqli_real_escape_string(
        $conn,
        trim($_POST['username'])
    );

    $password=trim($_POST['password']);

    $sql="SELECT * FROM admin WHERE username='$username' LIMIT 1";

    $result=mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)==1)
    {

        $admin=mysqli_fetch_assoc($result);

        /* If password_hash() is used */

        if(password_verify($password,$admin['password']) || $password==$admin['password'])
        {

            $_SESSION['admin']=$admin['username'];

            $_SESSION['admin_id']=$admin['id'];

            echo "<script>

            alert('Login Successful');

            window.location='dashboard.php';

            </script>";

            exit();

        }
        else
        {

            $error="Invalid Password";

        }

    }
    else
    {

        $error="Invalid Username";

    }

}

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>

Admin Login

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<style>

body{

background:#f4f6f9;

}

.login-box{

max-width:420px;

margin:90px auto;

}

</style>

</head>

<body>

<div class="container">

<div class="login-box">

<div class="card shadow">

<div class="card-header bg-dark text-white text-center">

<h3>

🔐 Admin Login

</h3>

</div>

<div class="card-body">

<?php

if($error!="")
{

?>

<div class="alert alert-danger">

<?php echo $error; ?>

</div>

<?php

}

?>

<form method="POST">

<div class="mb-3">

<label>

Username

</label>

<input
type="text"
name="username"
class="form-control"
required>

</div>

<div class="mb-3">

<label>

Password

</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<div class="d-grid">

<button
type="submit"
name="login"
class="btn btn-primary">

Login

</button>

</div>

</form>

</div>

<div class="card-footer text-center">

Insurance Management System

</div>

</div>

</div>

</div>

</body>

</html>