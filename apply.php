<?php
session_start();

include("includes/db.php");
include("includes/header.php");

// ===============================
// USER LOGIN CHECK
// ===============================

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ===============================
// PLAN ID CHECK
// ===============================

if(!isset($_GET['id']))
{
    echo "<div class='container mt-5'>
    <div class='alert alert-danger'>
    Invalid Insurance Plan.
    </div>
    </div>";

    include("includes/footer.php");
    exit();
}

$plan_id = intval($_GET['id']);

// ===============================
// GET PLAN DETAILS
// ===============================

$getPlan = mysqli_query($conn,"
SELECT *
FROM insurance_plans
WHERE id='$plan_id'
");

if(mysqli_num_rows($getPlan)==0)
{
    echo "<div class='container mt-5'>
    <div class='alert alert-danger'>
    Insurance Plan Not Found.
    </div>
    </div>";

    include("includes/footer.php");
    exit();
}

$plan = mysqli_fetch_assoc($getPlan);

// ===============================
// SUBMIT APPLICATION
// ===============================

if(isset($_POST['submit']))
{
    // Duplicate Policy Check

    $checkPolicy = mysqli_query($conn,"
    SELECT id
    FROM policy_applications
    WHERE user_id='$user_id'
    AND plan_id='$plan_id'
    AND status IN ('Pending','Approved')
    ");

    if(mysqli_num_rows($checkPolicy)>0)
    {
        echo "<script>
        alert('You have already applied for this policy.');
        window.location='status.php';
        </script>";
        exit();
    }

    $full_name = mysqli_real_escape_string($conn,$_POST['full_name']);
    $dob = mysqli_real_escape_string($conn,$_POST['dob']);
    $gender = mysqli_real_escape_string($conn,$_POST['gender']);
    $phone = mysqli_real_escape_string($conn,$_POST['phone']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $occupation = mysqli_real_escape_string($conn,$_POST['occupation']);
    $annual_income = mysqli_real_escape_string($conn,$_POST['annual_income']);
    $address = mysqli_real_escape_string($conn,$_POST['address']);

    $insert = mysqli_query($conn,"
    INSERT INTO policy_applications
    (
        user_id,
        plan_id,
        full_name,
        dob,
        gender,
        phone,
        email,
        address,
        occupation,
        annual_income,
        status
    )
    VALUES
    (
        '$user_id',
        '$plan_id',
        '$full_name',
        '$dob',
        '$gender',
        '$phone',
        '$email',
        '$address',
        '$occupation',
        '$annual_income',
        'Pending'
    )
    ");

    if($insert)
    {
        $policy_id = mysqli_insert_id($conn);

        // Create the first premium payment schedule for this application
        $premium_amount = $plan['premium'];
        $due_date = date('Y-m-d');

        $scheduleInsert = mysqli_query($conn,"
        INSERT INTO premium_schedule
        (
            user_id,
            policy_id,
            premium_amount,
            due_date,
            status
        )
        VALUES
        (
            '$user_id',
            '$plan_id',
            '$premium_amount',
            '$due_date',
            'Pending'
        )
        ");

        if(!$scheduleInsert)
        {
            mysqli_query($conn,"
            DELETE FROM policy_applications
            WHERE id='$policy_id'
            ");

            echo "<div class='alert alert-danger mt-3'>
            Unable to create premium payment schedule:
            ".mysqli_error($conn)."
            </div>";
            exit();
        }

        $schedule_id = mysqli_insert_id($conn);

        // Notification

        $title = "Policy Application Submitted";

        $message = "Your insurance application has been submitted successfully and is waiting for admin approval.";

        mysqli_query($conn,"
        INSERT INTO notifications
        (
            user_id,
            title,
            message
        )
        VALUES
        (
            '$user_id',
            '$title',
            '$message'
        )
        ");

        echo "<script>
        alert('Application Submitted Successfully');
        window.location='upload_documents.php?policy_id=$policy_id&schedule_id=$schedule_id';
        </script>";

        exit();
    }
    else
    {
        echo "<div class='alert alert-danger mt-3'>
        ".mysqli_error($conn)."
        </div>";
    }
}
?>

<div class="container mt-5 mb-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h2 class="text-center">

Apply Insurance Policy

</h2>

</div>

<div class="card-body">

<div class="alert alert-info">

<h4>

<?php echo $plan['plan_name']; ?>

</h4>

<p>

<?php echo $plan['description']; ?>

</p>

<b>

Premium :

₹<?php echo $plan['premium']; ?>

</b>

</div>

<form method="POST">

<div class="row">

    <div class="col-md-6 mb-3">
        <label class="form-label">Full Name</label>
        <input
            type="text"
            name="full_name"
            class="form-control"
            required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Date of Birth</label>
        <input
            type="date"
            name="dob"
            class="form-control"
            required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Gender</label>

        <select
            name="gender"
            class="form-select"
            required>

            <option value="">Select Gender</option>

            <option value="Male">Male</option>

            <option value="Female">Female</option>

            <option value="Other">Other</option>

        </select>

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Phone Number

        </label>

        <input
            type="text"
            name="phone"
            class="form-control"
            maxlength="10"
            required>

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Email Address

        </label>

        <input
            type="email"
            name="email"
            class="form-control"
            required>

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Occupation

        </label>

        <input
            type="text"
            name="occupation"
            class="form-control"
            required>

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Annual Income

        </label>

        <input
            type="number"
            name="annual_income"
            class="form-control"
            required>

    </div>

    <div class="col-md-12 mb-3">

        <label class="form-label">

            Address

        </label>

        <textarea
            name="address"
            class="form-control"
            rows="4"
            required></textarea>

    </div>

</div>

<div class="text-center mt-4">

    <button
        type="submit"
        name="submit"
        class="btn btn-primary btn-lg">

        Submit Application

    </button>

    <a
        href="plans.php"
        class="btn btn-secondary btn-lg ms-2">

        Back

    </a>

</div>

</form>

</div>

</div>

</div>

<?php include("includes/footer.php"); ?>