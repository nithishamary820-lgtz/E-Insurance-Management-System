<?php
session_start();

if(!isset($_SESSION['admin']))
{
    header("Location: admin_login.php");
    exit();
}

include("../includes/db.php");
include("header.php");

$id = intval($_GET['id']);

$sql = "

SELECT

policy_applications.*,

insurance_plans.plan_name,

users.name,
users.email AS user_email,
users.phone,

users.aadhaar_number,
users.smart_card_number,

users.school_name,
users.college_name,
users.course,
users.address,

documents.aadhaar_file,
documents.smartcard_file,
documents.photo_file,
documents.income_certificate,
documents.address_proof

FROM policy_applications

INNER JOIN insurance_plans
ON policy_applications.plan_id = insurance_plans.id

INNER JOIN users
ON users.id = policy_applications.user_id

LEFT JOIN documents
ON documents.policy_id = policy_applications.id

WHERE policy_applications.id='$id'

";

$result = mysqli_query($conn,$sql);

if(mysqli_num_rows($result)==0)
{
    echo "<h3 class='text-center mt-5'>Application Not Found</h3>";
    exit();
}

$row=mysqli_fetch_assoc($result);

?>

<div class="container mt-5 mb-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>

📄 Policy Application Details

</h3>

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>

<th width="30%">Applicant Name</th>

<td><?php echo htmlspecialchars($row['full_name']); ?></td>

</tr>

<tr>

<th>Date of Birth</th>

<td><?php echo htmlspecialchars($row['dob']); ?></td>

</tr>

<tr>

<th>Gender</th>

<td><?php echo htmlspecialchars($row['gender']); ?></td>

</tr>

<tr>

<th>Phone</th>

<td><?php echo htmlspecialchars($row['phone']); ?></td>

</tr>

<tr>

<th>Email</th>

<td><?php echo htmlspecialchars($row['email']); ?></td>

</tr>

<tr>

<th>Address</th>

<td><?php echo nl2br(htmlspecialchars($row['address'])); ?></td>

</tr>

<tr>

<th>Occupation</th>

<td><?php echo htmlspecialchars($row['occupation']); ?></td>

</tr>

<tr>

<th>Annual Income</th>

<td>

₹ <?php echo number_format($row['annual_income']); ?>

</td>

</tr>

<tr>

<th>Insurance Plan</th>

<td>

<?php echo htmlspecialchars($row['plan_name']); ?>

</td>

</tr>

<tr>

<th>Application Status</th>

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

</tr>

<tr>

<th>Aadhaar Number</th>

<td>

<?php echo htmlspecialchars($row['aadhaar_number']); ?>

</td>

</tr>

<tr>

<th>Smart Card Number</th>

<td>

<?php echo htmlspecialchars($row['smart_card_number']); ?>

</td>

</tr>

<tr>

<th>School</th>

<td>

<?php echo htmlspecialchars($row['school_name']); ?>

</td>

</tr>

<tr>

<th>College</th>

<td>

<?php echo htmlspecialchars($row['college_name']); ?>

</td>

</tr>

<tr>

<th>Course</th>

<td>

<?php echo htmlspecialchars($row['course']); ?>

</td>

</tr>

</table>

<hr>

<h4>

📂 Uploaded Documents

</h4>

<div class="row">

<div class="col-md-4 mb-3">

<?php

if(!empty($row['aadhaar_file']))
{

?>

<a
class="btn btn-primary w-100"
target="_blank"
href="../uploads/aadhaar/<?php echo $row['aadhaar_file']; ?>">

View Aadhaar

</a>

<?php
}
else
{
echo "<button class='btn btn-secondary w-100' disabled>No Aadhaar</button>";
}

?>

</div>

<div class="col-md-4 mb-3">

<?php

if(!empty($row['smartcard_file']))
{

?>

<a
class="btn btn-success w-100"
target="_blank"
href="../uploads/smartcard/<?php echo $row['smartcard_file']; ?>">

View Smart Card

</a>

<?php
}
else
{
echo "<button class='btn btn-secondary w-100' disabled>No Smart Card</button>";
}

?>

</div>

<div class="col-md-4 mb-3">

<?php

if(!empty($row['photo_file']))
{

?>

<a
class="btn btn-info w-100"
target="_blank"
href="../uploads/photo/<?php echo $row['photo_file']; ?>">

View Photo

</a>

<?php
}
else
{
echo "<button class='btn btn-secondary w-100' disabled>No Photo</button>";
}

?>

</div>

<div class="col-md-6 mb-3">

<?php

if(!empty($row['income_certificate']))
{

?>

<a
class="btn btn-warning w-100"
target="_blank"
href="../uploads/income/<?php echo $row['income_certificate']; ?>">

Income Certificate

</a>

<?php
}
else
{
echo "<button class='btn btn-secondary w-100' disabled>No Income Certificate</button>";
}

?>

</div>

<div class="col-md-6 mb-3">

<?php

if(!empty($row['address_proof']))
{

?>

<a
class="btn btn-dark w-100"
target="_blank"
href="../uploads/address/<?php echo $row['address_proof']; ?>">

Address Proof

</a>

<?php
}
else
{
echo "<button class='btn btn-secondary w-100' disabled>No Address Proof</button>";
}

?>

</div>

</div>

<hr>

<div class="text-center">

<?php if($row['status']=="Pending"){ ?>

<a
href="applications.php?approve=<?php echo $row['id'];?>"
class="btn btn-success"
onclick="return confirm('Approve this application?');">

✅ Approve Policy

</a>

<a
href="applications.php?reject=<?php echo $row['id'];?>"
class="btn btn-danger"
onclick="return confirm('Reject this application?');">

❌ Reject Policy

</a>

<?php } else { ?>

<button class="btn btn-secondary" disabled>

Application Already Processed

</button>

<?php } ?>

<a
href="applications.php"
class="btn btn-secondary">

⬅ Back

</a>

</div>

</div>

</div>

</div>

<?php include("../includes/footer.php"); ?>