<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['admin']))
{
    header("Location: admin_login.php");
    exit();
}

if(isset($_POST['save']))
{
    $plan_name = $_POST['plan_name'];
    $description = $_POST['description'];
    $premium = $_POST['premium'];
    $duration = $_POST['duration'];
    $coverage = $_POST['coverage'];

    $sql = "INSERT INTO insurance_plans(plan_name,description,premium,duration,coverage)
            VALUES('$plan_name','$description','$premium','$duration','$coverage')";

    if(mysqli_query($conn,$sql))
    {
        echo "<script>
        alert('Plan Added Successfully');
        window.location='plans.php';
        </script>";
    }
    else
    {
        echo "Error : ".mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Insurance Plan</title>
<link rel="stylesheet" href="../css/style.css">
</head>

<body>

<div class="container">

<h2 align="center">Add New Insurance Plan</h2>

<form method="POST">

<label>Plan Name</label>
<input type="text" name="plan_name" required>

<label>Description</label>
<textarea name="description" required></textarea>

<label>Premium</label>
<input type="number" name="premium" required>

<label>Duration</label>
<input type="text" name="duration" required>

<label>Coverage</label>
<input type="text" name="coverage" required>

<br><br>

<input type="submit" name="save" value="Add Plan" class="btn">

</form>

<br>

<a href="plans.php" class="btn">Back</a>

</div>

</body>
</html>