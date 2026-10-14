<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['admin']))
{
    header("Location: admin_login.php");
    exit();
}

$result = mysqli_query($conn,"SELECT * FROM insurance_plans");
?>

<!DOCTYPE html>
<html>

<head>

<title>Insurance Plans</title>

<link rel="stylesheet" href="../css/style.css">

</head>

<body>

<h1 align="center">Insurance Plans</h1>

<br>

<center>

<a href="add_plan.php" class="btn">+ Add New Plan</a>

</center>

<br>

<table border="1" width="100%" cellpadding="10">

<tr style="background:#003366;color:white;">

<th>ID</th>

<th>Plan Name</th>

<th>Description</th>

<th>Premium</th>

<th>Duration</th>

<th>Coverage</th>

<th>Action</th>

</tr>

<?php

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['plan_name']; ?></td>

<td><?php echo $row['description']; ?></td>

<td>₹<?php echo $row['premium']; ?></td>

<td><?php echo $row['duration']; ?></td>

<td><?php echo $row['coverage']; ?></td>

<td>

<a href="edit_plan.php?id=<?php echo $row['id']; ?>">Edit</a>

|

<a href="delete_plan.php?id=<?php echo $row['id']; ?>">Delete</a>

</td>

</tr>

<?php

}

?>

</table>

<br>

<center>

<a href="dashboard.php" class="btn">Back to Dashboard</a>

</center>

</body>

</html>