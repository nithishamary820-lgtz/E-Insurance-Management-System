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
   APPROVE APPLICATION
========================== */

if(isset($_GET['approve']))
{
    $id = intval($_GET['approve']);

    mysqli_query($conn,"
    UPDATE policy_applications
    SET status='Approved'
    WHERE id='$id'
    ");

    $get = mysqli_query($conn,"
    SELECT user_id
    FROM policy_applications
    WHERE id='$id'
    ");

    if(mysqli_num_rows($get)>0)
    {
        $data = mysqli_fetch_assoc($get);

        $user_id = $data['user_id'];

        $title = "Policy Approved";
        $message = "Congratulations! Your insurance policy has been approved.";

        mysqli_query($conn,"
        INSERT INTO notifications(user_id,title,message)
        VALUES('$user_id','$title','$message')
        ");
    }

    echo "<script>
    alert('Policy Approved Successfully');
    window.location='applications.php';
    </script>";
    exit();
}

/* ==========================
   REJECT APPLICATION
========================== */

if(isset($_GET['reject']))
{
    $id = intval($_GET['reject']);

    mysqli_query($conn,"
    UPDATE policy_applications
    SET status='Rejected'
    WHERE id='$id'
    ");

    $get = mysqli_query($conn,"
    SELECT user_id
    FROM policy_applications
    WHERE id='$id'
    ");

    if(mysqli_num_rows($get)>0)
    {
        $data = mysqli_fetch_assoc($get);

        $user_id = $data['user_id'];

        $title = "Policy Rejected";
        $message = "Sorry! Your insurance policy application has been rejected.";

        mysqli_query($conn,"
        INSERT INTO notifications(user_id,title,message)
        VALUES('$user_id','$title','$message')
        ");
    }

    echo "<script>
    alert('Policy Rejected Successfully');
    window.location='applications.php';
    </script>";
    exit();
}

/* ==========================
   SEARCH
========================== */

$search = "";

if(isset($_GET['search']))
{
    $search = mysqli_real_escape_string($conn,$_GET['search']);
}

/* ==========================
   FETCH APPLICATIONS
========================== */

$sql = "

SELECT

policy_applications.*,

users.name AS user_name,
users.email,

insurance_plans.plan_name

FROM policy_applications

INNER JOIN users
ON policy_applications.user_id = users.id

INNER JOIN insurance_plans
ON policy_applications.plan_id = insurance_plans.id

WHERE
users.name LIKE '%$search%'
OR users.email LIKE '%$search%'
OR insurance_plans.plan_name LIKE '%$search%'

ORDER BY policy_applications.id DESC

";

$result = mysqli_query($conn,$sql);

?>

<div class="container mt-4">

<h2 class="text-center mb-4">
📄 Policy Applications
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
<th>Insurance Plan</th>
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

<td><?php echo htmlspecialchars($row['user_name']); ?></td>

<td><?php echo htmlspecialchars($row['email']); ?></td>

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

<a
href="view_application.php?id=<?php echo $row['id']; ?>"
class="btn btn-info btn-sm mb-1">

View

</a>

<br>

<?php if($row['status']=="Pending"){ ?>

<a
href="applications.php?approve=<?php echo $row['id']; ?>"
class="btn btn-success btn-sm mb-1"
onclick="return confirm('Approve this application?');">

Approve

</a>

<br>

<a
href="applications.php?reject=<?php echo $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Reject this application?');">

Reject

</a>

<?php } else { ?>

<span class="badge bg-secondary">
Completed
</span>

<?php } ?>

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

No Applications Found

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