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

claims.*,

insurance_plans.plan_name

FROM claims

INNER JOIN policy_applications
ON claims.policy_id=policy_applications.id

INNER JOIN insurance_plans
ON policy_applications.plan_id=insurance_plans.id

WHERE claims.user_id='$user_id'

ORDER BY claims.id DESC

";

$result=mysqli_query($conn,$sql);

?>

<div class="container mt-5 mb-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>

📑 My Insurance Claims

</h3>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Policy</th>

<th>Claim Amount</th>

<th>Reason</th>

<th>Document</th>

<th>Status</th>

<th>Date</th>

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

₹ <?php echo number_format($row['claim_amount']); ?>

</td>

<td>

<?php echo htmlspecialchars($row['reason']); ?>

</td>

<td>

<?php

if(!empty($row['document']))
{

?>

<a
href="uploads/claims/<?php echo $row['document']; ?>"
target="_blank"
class="btn btn-sm btn-primary">

View Document

</a>

<?php

}
else
{

echo "<span class='text-danger'>No Document</span>";

}

?>

</td>

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

if(isset($row['claim_date']))
{
    echo $row['claim_date'];
}
elseif(isset($row['created_at']))
{
    echo $row['created_at'];
}
else
{
    echo "-";
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

<td colspan="7" class="text-center">

No Claims Found

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
href="claim.php"
class="btn btn-success">

➕ New Claim

</a>

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