<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['admin']))
{
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'];

$result = mysqli_query($conn,"SELECT * FROM insurance_plans WHERE id='$id'");
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update']))
{
    $plan_name = $_POST['plan_name'];
    $description = $_POST['description'];
    $premium = $_POST['premium'];
    $duration = $_POST['duration'];
    $coverage = $_POST['coverage'];

    $sql = "UPDATE insurance_plans SET
            plan_name='$plan_name',
            description='$description',
            premium='$premium',
            duration='$duration',
            coverage='$coverage'
            WHERE id='$id'";

    if(mysqli_query($conn,$sql))
    {
        echo "<script>
        alert('Plan Updated Successfully');
        window.location='plans.php';
        </script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Plan</title>
<link rel="stylesheet" href="../css/style.css">
</head>

<body>

<div class="container">

<h2 align="center">Edit Insurance Plan</h2>

<form method="POST">

<label>Plan Name</label>
<input type="text" name="plan_name" value="<?php echo $row['plan_name']; ?>" required>

<label>Description</label>
<textarea name="description" required><?php echo $row['description']; ?></textarea>

<label>Premium</label>
<input type="number" name="premium" value="<?php echo $row['premium']; ?>" required>

<label>Duration</label>
<input type="text" name="duration" value="<?php echo $row['duration']; ?>" required>

<label>Coverage</label>
<input type="text" name="coverage" value="<?php echo $row['coverage']; ?>" required>

<br><br>

<input type="submit" name="update" value="Update Plan" class="btn">

</form>

<br>

<a href="plans.php" class="btn">Back</a>

</div>

</body>
</html>