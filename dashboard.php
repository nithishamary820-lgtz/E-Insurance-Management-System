<?php
session_start();

if(!isset($_SESSION['admin']))
{
    header("Location: admin_login.php");
    exit();
}

include("../includes/db.php");
include("header.php");

/* ===========================
   DASHBOARD STATISTICS
=========================== */

$total_users = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) total FROM users"))['total'];

$total_policies = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) total FROM policy_applications"))['total'];

$approved = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) total
FROM policy_applications
WHERE status='Approved'"))['total'];

$pending = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) total
FROM policy_applications
WHERE status='Pending'"))['total'];

$rejected = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) total
FROM policy_applications
WHERE status='Rejected'"))['total'];

$total_claims = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) total
FROM claims"))['total'];

$pending_claims = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) total
FROM claims
WHERE status='Pending'"))['total'];

$total_premium = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT SUM(amount) total
FROM payments"))['total'];

$messages = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) total
FROM contact_messages"))['total'];

$pending_documents = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) total
FROM users
WHERE document_status='Pending'"))['total'];

?>

<div class="container-fluid mt-4">

<div class="row">

<div class="col-md-12">

<div class="card shadow">

<div class="card-body">

<h2>
🎯 Insurance Management System
</h2>

<p class="text-muted">

Welcome Admin,

Manage Users, Policies, Payments, Claims and Reports.

</p>

</div>

</div>

</div>

</div>

<br>

<div class="row">

<div class="col-md-3 mb-4">

<div class="card bg-primary text-white shadow">

<div class="card-body text-center">

<h5>Total Users</h5>

<h2><?php echo $total_users; ?></h2>

</div>

</div>

</div>



<div class="col-md-3 mb-4">

<div class="card bg-success text-white shadow">

<div class="card-body text-center">

<h5>Total Policies</h5>

<h2><?php echo $total_policies; ?></h2>

</div>

</div>

</div>



<div class="col-md-3 mb-4">

<div class="card bg-warning shadow">

<div class="card-body text-center">

<h5>Approved</h5>

<h2><?php echo $approved; ?></h2>

</div>

</div>

</div>



<div class="col-md-3 mb-4">

<div class="card bg-danger text-white shadow">

<div class="card-body text-center">

<h5>Pending</h5>

<h2><?php echo $pending; ?></h2>

</div>

</div>

</div>

</div>





<div class="row">

<div class="col-md-3 mb-4">

<div class="card bg-info text-white shadow">

<div class="card-body text-center">

<h5>Total Claims</h5>

<h2><?php echo $total_claims; ?></h2>

</div>

</div>

</div>



<div class="col-md-3 mb-4">

<div class="card bg-secondary text-white shadow">

<div class="card-body text-center">

<h5>Pending Claims</h5>

<h2><?php echo $pending_claims; ?></h2>

</div>

</div>

</div>



<div class="col-md-3 mb-4">

<div class="card bg-dark text-white shadow">

<div class="card-body text-center">

<h5>Total Premium</h5>

<h2>

₹<?php echo ($total_premium)?$total_premium:0; ?>

</h2>

</div>

</div>

</div>



<div class="col-md-3 mb-4">

<div class="card bg-warning shadow">

<div class="card-body text-center">

<h5>Messages</h5>

<h2><?php echo $messages; ?></h2>

</div>

</div>

</div>

</div>





<div class="row">

<div class="col-md-4 mb-4">

<div class="card border-success">

<div class="card-body">

<h5>

📄 Pending Document Verification

</h5>

<h2>

<?php echo $pending_documents; ?>

</h2>

</div>

</div>

</div>



<div class="col-md-4 mb-4">

<div class="card border-danger">

<div class="card-body">

<h5>

❌ Rejected Policies

</h5>

<h2>

<?php echo $rejected; ?>

</h2>

</div>

</div>

</div>



<div class="col-md-4 mb-4">

<div class="card border-primary">

<div class="card-body">

<h5>

💰 Revenue

</h5>

<h2>

₹<?php echo ($total_premium)?$total_premium:0; ?>

</h2>

</div>

</div>

</div>

</div>

<hr>

<h3>

⚡ Quick Menu

</h3>

<div class="row">

<div class="col-md-3 mb-3">

<a href="users.php" class="btn btn-primary w-100">

👥 Manage Users

</a>

</div>

<div class="col-md-3 mb-3">

<a href="plans.php" class="btn btn-success w-100">

🛡 Insurance Plans

</a>

</div>

<div class="col-md-3 mb-3">

<a href="applications.php" class="btn btn-info w-100">

📄 Applications

</a>

</div>

<div class="col-md-3 mb-3">

<a href="payments.php" class="btn btn-warning w-100">

💳 Payments

</a>

</div>

<div class="col-md-3 mb-3">

<a href="claims.php" class="btn btn-danger w-100">

❤️ Claims

</a>

</div>

<div class="col-md-3 mb-3">

<a href="chat_users.php" class="btn btn-dark w-100">

💬 Chats

</a>

</div>

<div class="col-md-3 mb-3">

<a href="contact_messages.php" class="btn btn-secondary w-100">

📩 Messages

</a>

</div>

<div class="col-md-3 mb-3">

<a href="dashboard.php" class="btn btn-success w-100">

🏠 Refresh

</a>

</div>

</div>

<hr>

<h3>

📄 Recent Policy Applications

</h3>

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>User</th>

<th>Policy</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php

$result=mysqli_query($conn,"
SELECT
policy_applications.id,
users.name,
insurance_plans.plan_name,
policy_applications.status

FROM policy_applications

JOIN users
ON users.id=policy_applications.user_id

JOIN insurance_plans
ON insurance_plans.id=policy_applications.plan_id

ORDER BY policy_applications.id DESC
LIMIT 10
");

while($row=mysqli_fetch_assoc($result))
{

$status=$row['status'];

if($status=="Approved")
{
$status="<span class='badge bg-success'>Approved</span>";
}
elseif($status=="Rejected")
{
$status="<span class='badge bg-danger'>Rejected</span>";
}
else
{
$status="<span class='badge bg-warning text-dark'>Pending</span>";
}

?>
<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo htmlspecialchars($row['name']); ?></td>

<td><?php echo htmlspecialchars($row['plan_name']); ?></td>

<td><?php echo $status; ?></td>

</tr>

<?php
}
?>

</tbody>

</table>

<hr>

<h3 class="mt-5">

👥 Recently Registered Users

</h3>

<table class="table table-bordered table-striped">

<thead class="table-primary">

<tr>

<th>ID</th>

<th>Name</th>

<th>Email</th>

<th>Phone</th>

<th>Email Verified</th>

</tr>

</thead>

<tbody>

<?php

$users=mysqli_query($conn,"
SELECT *
FROM users
ORDER BY id DESC
LIMIT 10
");

while($u=mysqli_fetch_assoc($users))
{

?>

<tr>

<td><?php echo $u['id']; ?></td>

<td><?php echo htmlspecialchars($u['name']); ?></td>

<td><?php echo htmlspecialchars($u['email']); ?></td>

<td><?php echo htmlspecialchars($u['phone']); ?></td>

<td>

<?php

if(isset($u['email_verified']) && $u['email_verified']=="Yes")
{
    echo "<span class='badge bg-success'>Verified</span>";
}
else
{
    echo "<span class='badge bg-danger'>Not Verified</span>";
}

?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

<hr>

<h3 class="mt-5">

💳 Recent Premium Payments

</h3>

<table class="table table-bordered table-hover">

<thead class="table-success">

<tr>

<th>ID</th>

<th>User ID</th>

<th>Amount</th>

<th>Method</th>

<th>Status</th>

<th>Date</th>

</tr>

</thead>

<tbody>

<?php

$payments=mysqli_query($conn,"
SELECT *
FROM payments
ORDER BY id DESC
LIMIT 10
");

while($pay=mysqli_fetch_assoc($payments))
{

?>

<tr>

<td><?php echo $pay['id']; ?></td>

<td><?php echo $pay['user_id']; ?></td>

<td>₹<?php echo $pay['amount']; ?></td>

<td><?php echo htmlspecialchars($pay['payment_method']); ?></td>

<td>

<?php

$status = isset($pay['payment_status'])
            ? $pay['payment_status']
            : (isset($pay['status']) ? $pay['status'] : 'Success');

if($status=="Success")
{
    echo "<span class='badge bg-success'>Success</span>";
}
elseif($status=="Pending")
{
    echo "<span class='badge bg-warning text-dark'>Pending</span>";
}
else
{
    echo "<span class='badge bg-danger'>Failed</span>";
}

?>

</td>

<td>

<?php
echo isset($pay['payment_date'])
        ? $pay['payment_date']
        : "-";
?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

<hr>

<div class="row mt-4 mb-5">

<div class="col-md-3">

<a href="users.php" class="btn btn-primary w-100">

👥 Users

</a>

</div>

<div class="col-md-3">

<a href="applications.php" class="btn btn-success w-100">

📄 Applications

</a>

</div>

<div class="col-md-3">

<a href="claims.php" class="btn btn-danger w-100">

❤️ Claims

</a>

</div>

<div class="col-md-3">

<a href="payments.php" class="btn btn-warning w-100">

💳 Payments

</a>

</div>

</div>

</div>

<?php include("footer.php"); ?>