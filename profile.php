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

$result=mysqli_query($conn,"
SELECT *
FROM users
WHERE id='$user_id'
");

$row=mysqli_fetch_assoc($result);

if(isset($_POST['update']))
{

$name=mysqli_real_escape_string($conn,$_POST['name']);

$email=mysqli_real_escape_string($conn,$_POST['email']);

$phone=mysqli_real_escape_string($conn,$_POST['phone']);

$aadhaar=mysqli_real_escape_string($conn,$_POST['aadhaar_number']);

$smart=mysqli_real_escape_string($conn,$_POST['smart_card_number']);

$school=mysqli_real_escape_string($conn,$_POST['school_name']);

$college=mysqli_real_escape_string($conn,$_POST['college_name']);

$course=mysqli_real_escape_string($conn,$_POST['course']);

$address=mysqli_real_escape_string($conn,$_POST['address']);

mysqli_query($conn,"

UPDATE users

SET

name='$name',

email='$email',

phone='$phone',

aadhaar_number='$aadhaar',

smart_card_number='$smart',

school_name='$school',

college_name='$college',

course='$course',

address='$address'

WHERE id='$user_id'

");

echo "

<script>

alert('Profile Updated Successfully');

window.location='profile.php';

</script>

";

exit();

}

?>

<div class="container mt-5 mb-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3 class="text-center">

👤 My Profile

</h3>

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label>Full Name</label>

<input
type="text"
name="name"
class="form-control"
value="<?php echo htmlspecialchars($row['name']); ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
value="<?php echo htmlspecialchars($row['email']); ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label>Phone</label>

<input
type="text"
name="phone"
class="form-control"
value="<?php echo htmlspecialchars($row['phone']); ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label>Aadhaar Number</label>

<input
type="text"
name="aadhaar_number"
class="form-control"
value="<?php echo htmlspecialchars($row['aadhaar_number']); ?>">

</div>

<div class="col-md-6 mb-3">

<label>Smart Card Number</label>

<input
type="text"
name="smart_card_number"
class="form-control"
value="<?php echo htmlspecialchars($row['smart_card_number']); ?>">

</div>

<div class="col-md-6 mb-3">

<label>School Name</label>

<input
type="text"
name="school_name"
class="form-control"
value="<?php echo htmlspecialchars($row['school_name']); ?>">

</div>

<div class="col-md-6 mb-3">

<label>College Name</label>

<input
type="text"
name="college_name"
class="form-control"
value="<?php echo htmlspecialchars($row['college_name']); ?>">

</div>

<div class="col-md-6 mb-3">

<label>Course</label>

<input
type="text"
name="course"
class="form-control"
value="<?php echo htmlspecialchars($row['course']); ?>">

</div>

<div class="col-md-12 mb-3">

<label>Address</label>

<textarea
name="address"
class="form-control"
rows="4"><?php echo htmlspecialchars($row['address']); ?></textarea>

</div>

</div>

<div class="text-center">

<button
type="submit"
name="update"
class="btn btn-success btn-lg">

Update Profile

</button>

<a
href="dashboard.php"
class="btn btn-secondary btn-lg">

Back

</a>

</div>

</form>

<hr>

<div class="text-center">

<a
href="change_password.php"
class="btn btn-warning">

🔒 Change Password

</a>

</div>

</div>

</div>

</div>

<?php include("includes/footer.php"); ?>