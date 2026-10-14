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

/* ===========================
   DASHBOARD COUNTS
=========================== */

$total_count = mysqli_num_rows(mysqli_query($conn,"
SELECT * FROM policy_applications
WHERE user_id='$user_id'
"));

$pending_count = mysqli_num_rows(mysqli_query($conn,"
SELECT * FROM policy_applications
WHERE user_id='$user_id'
AND status='Pending'
"));

$approved_count = mysqli_num_rows(mysqli_query($conn,"
SELECT * FROM policy_applications
WHERE user_id='$user_id'
AND status='Approved'
"));

$rejected_count = mysqli_num_rows(mysqli_query($conn,"
SELECT * FROM policy_applications
WHERE user_id='$user_id'
AND status='Rejected'
"));

$claim_count = mysqli_num_rows(mysqli_query($conn,"
SELECT * FROM claims
WHERE user_id='$user_id'
"));

$notification_count = mysqli_num_rows(mysqli_query($conn,"
SELECT * FROM notifications
WHERE user_id='$user_id'
"));

?>

<div class="container py-5">

<h2 class="text-center mb-2">

Welcome,

<span class="text-primary">

<?php echo htmlspecialchars($_SESSION['user_name']); ?>

</span>

👋

</h2>

<hr>

<div class="row">

<div class="col-md-3 mb-3">
<div class="card shadow text-center">
<div class="card-body">
<h5>Total Policies</h5>
<h2 class="text-primary">
<?php echo $total_count; ?>
</h2>
</div>
</div>
</div>

<div class="col-md-3 mb-3">
<div class="card shadow text-center">
<div class="card-body">
<h5>Approved</h5>
<h2 class="text-success">
<?php echo $approved_count; ?>
</h2>
</div>
</div>
</div>

<div class="col-md-3 mb-3">
<div class="card shadow text-center">
<div class="card-body">
<h5>Pending</h5>
<h2 class="text-warning">
<?php echo $pending_count; ?>
</h2>
</div>
</div>
</div>

<div class="col-md-3 mb-3">
<div class="card shadow text-center">
<div class="card-body">
<h5>Rejected</h5>
<h2 class="text-danger">
<?php echo $rejected_count; ?>
</h2>
</div>
</div>
</div>

<div class="col-md-6 mb-3">
<div class="card shadow text-center">
<div class="card-body">
<h5>Total Claims</h5>
<h2 class="text-info">
<?php echo $claim_count; ?>
</h2>
</div>
</div>
</div>

<div class="col-md-6 mb-3">
<div class="card shadow text-center">
<div class="card-body">
<h5>Notifications</h5>
<h2 class="text-dark">
<?php echo $notification_count; ?>
</h2>
</div>
</div>
</div>

</div>

<hr>

<h3 class="text-center mb-4">

Quick Actions

</h3>

<div class="row">

<div class="col-md-4 mb-3">

<a href="plans.php"
class="btn btn-primary w-100">

📋 Apply New Policy

</a>

</div>

<div class="col-md-4 mb-3">

<a href="status.php"
class="btn btn-success w-100">

📄 Policy Status

</a>

</div>

<div class="col-md-4 mb-3">

<a href="premium_due.php"
class="btn btn-warning w-100">

💳 Premium Payment

</a>

</div>

<div class="col-md-4 mb-3">

<a href="claim.php"
class="btn btn-danger w-100">

❤️ Claim Insurance

</a>

</div>

<div class="col-md-4 mb-3">

<a href="my_claims.php"
class="btn btn-secondary w-100">

📑 My Claims

</a>

</div>

<div class="col-md-4 mb-3">

<a href="notifications.php"
class="btn btn-dark w-100">

🔔 Notifications

<?php

if($notification_count>0)
{
echo " ($notification_count)";
}

?>

</a>

</div>

<div class="col-md-4 mb-3">

<a href="premium_due.php"
class="btn btn-info w-100 text-white">

💰 Premium Due

</a>

</div>

<div class="col-md-4 mb-3">

<a href="user_chat.php"
class="btn btn-dark w-100">

💬 Chat With Admin

</a>

</div>

<div class="col-md-4 mb-3">

<a href="profile.php"
class="btn btn-primary w-100">

👤 My Profile

</a>

</div>

<div class="col-md-4 mb-3">

<a href="change_password.php"
class="btn btn-warning w-100">

🔑 Change Password

</a>

</div>

<div class="col-md-4 mb-3">

<a href="download_certificate.php"
class="btn btn-success w-100">

📥 Download Policy Certificate

</a>

</div>

<div class="col-md-4 mb-3">

<a href="download_receipt.php"
class="btn btn-secondary w-100">

🧾 Download Payment Receipt

</a>

</div>

</div>

<hr>

<h3 class="text-center mb-4">

Recent Policy Applications

</h3>

<div class="table-responsive">

<table class="table table-bordered table-striped">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Policy</th>
<th>Status</th>
<th>Applied Date</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php

$recent = mysqli_query($conn,"
SELECT
policy_applications.*,
insurance_plans.plan_name

FROM policy_applications

INNER JOIN insurance_plans
ON insurance_plans.id=policy_applications.plan_id

WHERE policy_applications.user_id='$user_id'

ORDER BY policy_applications.id DESC

LIMIT 5
");

if(mysqli_num_rows($recent)>0)
{

while($row=mysqli_fetch_assoc($recent))
{

?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo htmlspecialchars($row['plan_name']); ?></td>

<td>

<?php

if($row['status']=="Approved")
{
echo "<span class='badge bg-success'>Approved</span>";
}
elseif($row['status']=="Rejected")
{
echo "<span class='badge bg-danger'>Rejected</span>";
}
else
{
echo "<span class='badge bg-warning text-dark'>Pending</span>";
}

?>

</td>

<td>

<?php

if(isset($row['created_at']))
{
    echo $row['created_at'];
}
else
{
    echo "-";
}

?>

</td>

<td>

<a
href="status.php"
class="btn btn-primary btn-sm">

View

</a>

</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="5" class="text-center">

No Applications Found

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

<hr>

<div class="text-center mt-4">

<a
href="logout.php"
class="btn btn-danger btn-lg">

🚪 Logout

</a>

</div>

</div>

<?php include("includes/footer.php"); ?>