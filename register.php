<?php
session_start();
include("includes/db.php");

if(isset($_POST['register']))
{
    // ===========================
    // PERSONAL DETAILS
    // ===========================

    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));

    // ===========================
    // IDENTIFICATION DETAILS
    // ===========================

    $aadhaar_number = mysqli_real_escape_string($conn, trim($_POST['aadhaar_number']));
    $smart_card_number = mysqli_real_escape_string($conn, trim($_POST['smart_card_number']));

    // ===========================
    // EDUCATION DETAILS
    // ===========================

    $school_name = mysqli_real_escape_string($conn, trim($_POST['school_name']));
    $college_name = mysqli_real_escape_string($conn, trim($_POST['college_name']));
    $course = mysqli_real_escape_string($conn, trim($_POST['course']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));

    // ===========================
    // PASSWORD
    // ===========================

    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password != $confirm_password)
    {
        echo "<script>
        alert('Passwords do not match');
        window.location='register.php';
        </script>";
        exit();
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    // ===========================
    // CHECK DUPLICATES
    // ===========================

    $check = mysqli_query($conn,"
    SELECT *
    FROM users
    WHERE
    email='$email'
    OR
    aadhaar_number='$aadhaar_number'
    OR
    smart_card_number='$smart_card_number'
    ");

    if(mysqli_num_rows($check)>0)
    {
        echo "<script>
        alert('Email / Aadhaar / Smart Card already exists');
        window.location='register.php';
        </script>";
        exit();
    }
    // ===========================
    // INSERT USER
    // ===========================

    $sql = "INSERT INTO users
    (
        name,
        email,
        phone,
        aadhaar_number,
        smart_card_number,
        school_name,
        college_name,
        course,
        address,
        password,
        email_verified,
        document_status
    )
    VALUES
    (
        '$name',
        '$email',
        '$phone',
        '$aadhaar_number',
        '$smart_card_number',
        '$school_name',
        '$college_name',
        '$course',
        '$address',
        '$password',
        'No',
        'Not Submitted'
    )";

    if(mysqli_query($conn,$sql))
    {
        // ===========================
        // GENERATE OTP
        // ===========================

        $otp = rand(100000,999999);

        mysqli_query($conn,"
        DELETE FROM email_verification
        WHERE email='$email'
        ");

        mysqli_query($conn,"
        INSERT INTO email_verification
        (
            email,
            otp
        )
        VALUES
        (
            '$email',
            '$otp'
        )
        ");

        $_SESSION['verify_email'] = $email;

        include("send_otp.php");

        if(sendOTP($email,$otp))
        {
            echo "<script>
            alert('Registration Successful. OTP sent to your Email.');
            window.location='verify_email.php';
            </script>";
        }
        else
        {
            echo "<script>
            alert('Registration completed. OTP could not be sent.');
            window.location='login.php';
            </script>";
        }

        exit();
    }
    else
    {
        echo mysqli_error($conn);
    }
}

include("includes/header.php");
?>

<div class="container mt-5 mb-5">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h2 class="text-center">

User Registration

</h2>

</div>

<div class="card-body">

<form
method="POST">

<h4 class="mb-3">Personal Details</h4>

<div class="row">

<div class="col-md-6 mb-3">
<label class="form-label">Full Name</label>
<input
type="text"
name="name"
class="form-control"
required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Email</label>
<input
type="email"
name="email"
class="form-control"
required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Phone Number</label>
<input
type="text"
name="phone"
class="form-control"
required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Aadhaar Number</label>
<input
type="text"
name="aadhaar_number"
class="form-control"
maxlength="12"
required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Smart Card Number</label>
<input
type="text"
name="smart_card_number"
class="form-control"
required>
</div>

</div>

<hr>

<h4 class="mb-3">Education Details</h4>

<div class="row">

<div class="col-md-6 mb-3">
<label class="form-label">School Name</label>
<input
type="text"
name="school_name"
class="form-control"
required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">College Name</label>
<input
type="text"
name="college_name"
class="form-control"
required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Course</label>
<input
type="text"
name="course"
class="form-control"
required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Address</label>
<textarea
name="address"
class="form-control"
rows="3"
required></textarea>
</div>

</div>

<hr>

<h4 class="mb-3">

Create Password

</h4>

<div class="row">

<div class="col-md-6 mb-3">

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

<div class="col-md-6 mb-3">

<label class="form-label">

Confirm Password

</label>

<input
type="password"
name="confirm_password"
class="form-control"
placeholder="Confirm Password"
required>

</div>

</div>

<div class="d-grid mt-4">

<button
type="submit"
name="register"
class="btn btn-primary btn-lg">

Register

</button>

</div>

<div class="text-center mt-4">

Already have an account?

<a href="login.php">

Login Here

</a>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

<?php include("includes/footer.php"); ?>