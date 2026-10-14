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

policy_applications.id,

insurance_plans.plan_name,

insurance_plans.premium,

policy_applications.status,

policy_applications.created_at

FROM policy_applications

INNER JOIN insurance_plans

ON policy_applications.plan_id=insurance_plans.id

WHERE policy_applications.user_id='$user_id'

ORDER BY policy_applications.id DESC

";

$result=mysqli_query($conn,$sql);

?>

<div class="container mt-5 mb-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3 class="text-center">

📄 My Policy Status

</h3>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>Policy ID</th>

<th>Insurance Plan</th>

<th>Premium</th>

<th>Status</th>

<th>Applied Date</th>

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

<td>

<?php echo $row['id']; ?>

</td>

<td>

<?php echo htmlspecialchars($row['plan_name']); ?>

</td>

<td>

₹ <?php echo number_format($row['premium']); ?>

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

<?php

if($row['status']=="Approved")
{

?>

<a
href="download_policy.php?id=<?php echo $row['id']; ?>"
class="btn btn-success btn-sm">

📄 Download Policy

</a>

<?php

}
elseif($row['status']=="Pending")
{

echo "<span class='text-warning fw-bold'>Waiting for Approval</span>";

}
else
{

echo "<span class='text-danger fw-bold'>Application Rejected</span>";

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

No Policy Applications Found

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
href="plans.php"
class="btn btn-primary">

➕ Apply New Policy

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