<?php
session_start();

if(!isset($_SESSION['admin']))
{
    header("Location: admin_login.php");
    exit();
}

include("../includes/db.php");
include("header.php");

/* ==========================
   APPROVE CLAIM
========================== */

if(isset($_GET['approve']))
{
    $id = intval($_GET['approve']);

    mysqli_query($conn,"
    UPDATE claims
    SET status='Approved'
    WHERE id='$id'
    ");

    echo "<script>
    alert('Claim Approved Successfully');
    window.location='claims.php';
    </script>";

    exit();
}

/* ==========================
   REJECT CLAIM
========================== */

if(isset($_GET['reject']))
{
    $id = intval($_GET['reject']);

    mysqli_query($conn,"
    UPDATE claims
    SET status='Rejected'
    WHERE id='$id'
    ");

    echo "<script>
    alert('Claim Rejected Successfully');
    window.location='claims.php';
    </script>";

    exit();
}

/* ==========================
   SEARCH
========================== */

$search="";

if(isset($_GET['search']))
{
    $search=mysqli_real_escape_string($conn,$_GET['search']);
}

/* ==========================
   FETCH CLAIMS
========================== */

$sql="

SELECT

claims.*,

users.name,
users.email,

insurance_plans.plan_name

FROM claims

JOIN users
ON claims.user_id=users.id

JOIN policy_applications
ON claims.policy_id=policy_applications.id

JOIN insurance_plans
ON policy_applications.plan_id=insurance_plans.id

WHERE

users.name LIKE '%$search%'

OR

users.email LIKE '%$search%'

OR

insurance_plans.plan_name LIKE '%$search%'

ORDER BY claims.id DESC

";

$result=mysqli_query($conn,$sql);

?>

<div class="container mt-4">

<h2 class="text-center mb-4">

❤️ Claim Management

</h2>

<div class="card shadow p-3">

<form method="GET">

<div class="row">

<div class="col-md-10">

<input
type="text"
name="search"
class="form-control"
placeholder="Search User / Email / Policy"
value="<?php echo htmlspecialchars($search); ?>">

</div>

<div class="col-md-2">

<button class="btn btn-primary w-100">

Search

</button>

</div>

</div>

</form>

<br>

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>User</th>

<th>Email</th>

<th>Policy</th>

<th>Claim Amount</th>

<th>Reason</th>

<th>Document</th>

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

<td>

<?php echo $row['id']; ?>

</td>

<td>

<?php echo htmlspecialchars($row['name']); ?>

</td>

<td>

<?php echo htmlspecialchars($row['email']); ?>

</td>

<td>

<?php echo htmlspecialchars($row['plan_name']); ?>

</td>

<td>

₹<?php echo number_format($row['claim_amount'],2); ?>

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
href="../uploads/claims/<?php echo $row['document']; ?>"
target="_blank"
class="btn btn-info btn-sm">

View Document

</a>

<?php

}
else
{

echo "No Document";

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

<a
href="claims.php?approve=<?php echo $row['id']; ?>"
class="btn btn-success btn-sm mb-1"
onclick="return confirm('Approve this claim?');">

Approve

</a>

<br>

<a
href="claims.php?reject=<?php echo $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Reject this claim?');">

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

<td colspan="9" class="text-center">

No Claims Found

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

<br>

<div class="text-center">

<a
href="dashboard.php"
class="btn btn-secondary">

⬅ Back to Dashboard

</a>

</div>

</div>

</div>

<?php include("footer.php"); ?>