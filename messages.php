<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['admin']))
{
    header("Location: admin_login.php");
    exit();
}

$result = mysqli_query($conn,"SELECT * FROM contact_messages ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Contact Messages</title>
<link rel="stylesheet" href="../css/style.css">
</head>

<body>

<h2 align="center">Customer Contact Messages</h2>

<table border="1" width="100%" cellpadding="10">

<tr style="background:#003366;color:white;">
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Subject</th>
<th>Message</th>
<th>Date</th>
</tr>

<?php
while($row=mysqli_fetch_assoc($result))
{
?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['email']; ?></td>

<td><?php echo $row['subject']; ?></td>

<td><?php echo $row['message']; ?></td>

<td><?php echo $row['created_at']; ?></td>

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