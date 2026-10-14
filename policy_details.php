<?php

session_start();

include("includes/db.php");
include("includes/header.php");

if(!isset($_GET['id']))
{
    echo "<script>
    alert('Invalid Insurance Plan');
    window.location='plans.php';
    </script>";
    exit();
}

$plan_id = intval($_GET['id']);

$sql = "SELECT * FROM insurance_plans WHERE id='$plan_id'";

$result = mysqli_query($conn,$sql);

if(mysqli_num_rows($result)==0)
{
    echo "<script>
    alert('Insurance Plan Not Found');
    window.location='plans.php';
    </script>";
    exit();
}

$row = mysqli_fetch_assoc($result);

?>

<div class="container mt-5 mb-5">

<div class="card shadow">

<div class="card-header bg-primary text-white text-center">

<h2>

<?php echo htmlspecialchars($row['plan_name']); ?>

</h2>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-6">

<h5 class="text-primary">

Insurance Information

</h5>

<table class="table table-bordered">

<tr>

<th width="35%">Plan Name</th>

<td><?php echo htmlspecialchars($row['plan_name']); ?></td>

</tr>

<tr>

<th>Premium</th>

<td>

₹ <?php echo number_format($row['premium']); ?>

</td>

</tr>

<tr>

<th>Coverage</th>

<td>

₹ <?php echo number_format($row['coverage']); ?>

</td>

</tr>

<tr>

<th>Duration</th>

<td>

<?php echo htmlspecialchars($row['duration']); ?>

</td>

</tr>

</table>

</div>

<div class="col-md-6">

<h5 class="text-success">

Plan Description

</h5>

<div class="border rounded p-3 bg-light">

<?php

echo nl2br(htmlspecialchars($row['description']));

?>

</div>

</div>

</div>

<hr>

<h5 class="text-primary">

Policy Benefits

</h5>

<ul>

<li>✔ Financial protection for you and your family.</li>

<li>✔ Fast and secure claim settlement.</li>

<li>✔ 24×7 customer support.</li>

<li>✔ Affordable premium plans.</li>

<li>✔ Trusted insurance coverage.</li>

<li>✔ Secure online payment facility.</li>

<li>✔ Instant policy approval after verification.</li>

</ul>

<hr>

<div class="text-center">

<?php

if(isset($_SESSION['user_id']))
{

?>

<a
href="apply.php?id=<?php echo $row['id']; ?>"
class="btn btn-success btn-lg">

📝 Apply Now

</a>

<?php

}
else
{

?>

<a
href="login.php"
class="btn btn-warning btn-lg">

🔐 Login to Apply

</a>

<?php

}

?>

<a
href="plans.php"
class="btn btn-secondary btn-lg">

⬅ Back to Plans

</a>

</div>

</div>

</div>

</div>

<?php include("includes/footer.php"); ?>
