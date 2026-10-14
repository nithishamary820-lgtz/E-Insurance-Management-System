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
   SEARCH
========================== */

$search = "";

if(isset($_GET['search']))
{
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

/* ==========================
   FETCH PAYMENTS
========================== */

$sql = "
SELECT
payments.*,
users.name,
users.email

FROM payments

INNER JOIN users
ON payments.user_id = users.id

WHERE
users.name LIKE '%$search%'
OR users.email LIKE '%$search%'
OR payments.payment_method LIKE '%$search%'

ORDER BY payments.id DESC
";

$result = mysqli_query($conn,$sql);
?>

<div class="container mt-4">

<h2 class="text-center mb-4">
💳 Payment Management
</h2>

<div class="card shadow p-3">

<form method="GET">

<div class="row">

<div class="col-md-10">

<input
type="text"
name="search"
class="form-control"
placeholder="Search User / Email / Payment Method"
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

<th>Amount</th>

<th>Method</th>

<th>Status</th>

<th>Date</th>

<th>Transaction</th>

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

₹<?php echo number_format($row['amount'],2); ?>

</td>

<td>

<?php echo htmlspecialchars($row['payment_method']); ?>

</td>

<td>

<?php

$status = "";

if(isset($row['payment_status']))
{
    $status = $row['payment_status'];
}
elseif(isset($row['status']))
{
    $status = $row['status'];
}

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

if(isset($row['payment_date']))
{
    echo $row['payment_date'];
}
else
{
    echo "-";
}

?>

</td>

<td>

<?php

if(isset($row['transaction_id']) && $row['transaction_id']!="")
{
    echo $row['transaction_id'];
}
else
{
    echo "N/A";
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

<td colspan="8" class="text-center">

No Payment Records Found

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