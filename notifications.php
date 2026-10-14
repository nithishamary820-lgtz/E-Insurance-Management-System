<?php
session_start();

include("includes/db.php");
include("includes/header.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql="

SELECT *

FROM notifications

WHERE user_id='$user_id'

ORDER BY created_at DESC

";

$result=mysqli_query($conn,$sql);

?>

<div class="container mt-5 mb-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3 class="text-center">

🔔 Notification Center

</h3>

</div>

<div class="card-body">

<?php

if(mysqli_num_rows($result)>0)
{

while($row=mysqli_fetch_assoc($result))
{

?>

<div class="card mb-3 border-start border-primary border-4">

<div class="card-body">

<h5 class="fw-bold">

<?php echo htmlspecialchars($row['title']); ?>

</h5>

<p class="mb-2">

<?php echo nl2br(htmlspecialchars($row['message'])); ?>

</p>

<small class="text-muted">

📅 <?php echo $row['created_at']; ?>

</small>

</div>

</div>

<?php

}

}
else
{

?>

<div class="alert alert-info text-center">

<h5>

No Notifications Available

</h5>

<p>

You'll receive notifications here when:

</p>

<ul class="text-start">

<li>Policy is Approved</li>

<li>Policy is Rejected</li>

<li>Premium Payment Reminder</li>

<li>Claim Status Updated</li>

<li>System Announcements</li>

</ul>

</div>

<?php

}

?>

<div class="text-center mt-4">

<a
href="dashboard.php"
class="btn btn-secondary">

⬅ Back to Dashboard

</a>

</div>

</div>

</div>

</div>

<?php include("includes/footer.php"); ?>