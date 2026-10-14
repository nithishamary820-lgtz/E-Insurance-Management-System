<?php
include("../includes/db.php");
include("header.php");

$sql = "SELECT
policy_applications.id,
users.name,
insurance_plans.plan_name,
policy_applications.status

FROM policy_applications

INNER JOIN users
ON policy_applications.user_id = users.id

INNER JOIN insurance_plans
ON policy_applications.plan_id = insurance_plans.id

ORDER BY policy_applications.id DESC";

$result = mysqli_query($conn, $sql);
?>

<h2 class="text-center mb-4">Manage Policies</h2>

<table class="table table-bordered table-striped">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>User Name</th>
<th>Plan Name</th>
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

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['plan_name']; ?></td>

<td>

<?php

if($row['status']=="Pending")
{
    echo "<span class='badge bg-warning'>Pending</span>";
}
elseif($row['status']=="Approved")
{
    echo "<span class='badge bg-success'>Approved</span>";
}
else
{
    echo "<span class='badge bg-danger'>Rejected</span>";
}

?>

</td>

<td>

<a href="approve_policy.php?id=<?php echo $row['id']; ?>"
class="btn btn-success btn-sm">
Approve
</a>

<a href="reject_policy.php?id=<?php echo $row['id']; ?>"
class="btn btn-danger btn-sm">
Reject
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
No Policy Applications Found
</td>

</tr>

<?php
}
?>

</tbody>

</table>

<?php include("footer.php"); ?>