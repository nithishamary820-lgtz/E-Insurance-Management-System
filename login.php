<?php
session_start();
include("includes/db.php");

if(isset($_POST['login']))
{
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];

    if(empty($email) || empty($password))
    {
        echo "<script>alert('Please fill all fields');</script>";
    }
    else
    {
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) == 1)
        {
            $row = mysqli_fetch_assoc($result);

            if(password_verify($password, $row['password']))
            {
                if($row['email_verified'] != "Yes")
                {
                    $_SESSION['verify_email'] = $row['email'];

                    echo "<script>
                    alert('Please verify your email first.');
                    window.location='verify_email.php';
                    </script>";
                    exit();
                }

                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];

                header("Location: dashboard.php");
                exit();
            }
            else
            {
                echo "<script>alert('Incorrect Password');</script>";
            }
        }
        else
        {
            echo "<script>alert('Email Not Registered');</script>";
        }
    }
}

include("includes/header.php");
?>

<div class="row justify-content-center">

    <div class="col-md-5">

        <div class="card shadow p-4 mt-4">

            <h2 class="text-center mb-4">
                User Login
            </h2>

            <form method="POST">

                <div class="mb-3">

                    <label class="form-label">
                        Email Address
                    </label>

                    <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Enter Email Address"
                    required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Password
                    </label>

                    <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter Password"
                    required>

                </div>

                <div class="text-end mb-3">

                    <a href="forget_password.php">
                        Forget Password?
                    </a>

                </div>

                <button
                type="submit"
                name="login"
                class="btn btn-primary w-100">

                    Login

                </button>

            </form>

            <hr>

            <div class="text-center">

                Don't have an account?

                <br><br>

                <a
                href="register.php"
                class="btn btn-success">

                    Create New Account

                </a>

            </div>

        </div>

    </div>

</div>

<?php include("includes/footer.php"); ?>