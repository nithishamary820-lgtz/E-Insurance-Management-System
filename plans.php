<?php

session_start();

include("includes/db.php");
include("includes/header.php");

$sql="

SELECT *

FROM insurance_plans

ORDER BY premium ASC

";

$result=mysqli_query($conn,$sql);

?>

<div class="container mt-5 mb-5">

<h2 class="text-center mb-5">

🛡 Available Insurance Plans

</h2>

<div class="row">

<?php

if(mysqli_num_rows($result)>0)
{

while($row=mysqli_fetch_assoc($result))
{

?>

<div class="col-lg-4 col-md-6 mb-4">

<div class="card shadow h-100">

<div class="card-header bg-primary text-white text-center">

<h4>

<?php echo htmlspecialchars($row['plan_name']); ?>

</h4>

</div>

<div class="card-body">

<p>

<?php echo nl2br(htmlspecialchars($row['description'])); ?>

</p>

<hr>

<p>

<b>Premium</b><br>

₹ <?php echo number_format($row['premium']); ?>

</p>

<p>

<b>Duration</b><br>

<?php echo htmlspecialchars($row['duration']); ?>

</p>

<p>

<b>Coverage Amount</b><br>

₹ <?php echo htmlspecialchars($row['coverage']); ?>

</p>

<hr>

<div class="d-grid gap-2">

<?php

if(isset($_SESSION['user_id']))
{

?>

<a
href="apply.php?id=<?php echo $row['id']; ?>"
class="btn btn-success">

📝 Apply Now

</a>

<?php

}
else
{

?>

<a
href="login.php"
class="btn btn-warning">

🔐 Login to Apply

</a>

<?php

}

?>

<a
href="policy_details.php?id=<?php echo $row['id']; ?>"
class="btn btn-outline-primary">

📄 View Details

</a>

</div>

</div>

</div>

</div>

<?php

}

}
else
{

?>

<div class="col-12">

<div class="alert alert-warning text-center">

<h4>No Insurance Plans Available</h4>

<p>Please check back later.</p>

</div>

</div>

<?php

}

?>

</div>

<div class="text-center mt-4">

<a
href="index.php"
class="btn btn-secondary">

🏠 Back to Home

</a>

</div>

</div>

<?php include("includes/footer.php"); ?>