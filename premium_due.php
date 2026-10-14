<?php
session_start();

include("includes/db.php");
include("includes/header.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id=$_SESSION['user_id'];

$sql="

SELECT

premium_schedule.*,

insurance_plans.plan_name

FROM premium_schedule

INNER JOIN insurance_plans

ON premium_schedule.policy_id=insurance_plans.id

WHERE premium_schedule.user_id='$user_id'

ORDER BY premium_schedule.due_date ASC

";

$result=mysqli_query($conn,$sql);

?>

<div class="container mt-5 mb-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>

💳 Premium Due Details

</h3>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Insurance Plan</th>

<th>Premium Amount</th>

<th>Due Date</th>

<th>Status</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($result)>0)
{

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td><?php echo $row['id']; ?></td>

<td>

<?php echo htmlspecialchars($row['plan_name']); ?>

</td>

<td>

₹ <?php echo number_format($row['premium_amount']); ?>

</td>

<td>

<?php echo htmlspecialchars($row['due_date']); ?>

</td>

<td>

<?php

if($row['status']=="Paid")
{

echo "<span class='badge bg-success'>Paid</span>";

}
elseif($row['status']=="Pending")
{

echo "<span class='badge bg-warning text-dark'>Pending</span>";

}
else
{

echo "<span class='badge bg-danger'>Overdue</span>";

}

?>

</td>

<td>

<?php

if($row['status']=="Pending")
{

?>

<a
href="payment.php?id=<?php echo $row['id']; ?>"
class="btn btn-success btn-sm">

💳 Pay Now

</a>

<?php

}
else
{

?>

<span class="text-success fw-bold">

✔ Payment Completed

</span>

<?php

}

?>

</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="6" class="text-center">

No Premium Due Found

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

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