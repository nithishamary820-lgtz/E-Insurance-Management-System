<?php
session_start();

include("includes/db.php");
include("includes/header.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if(!isset($_GET['policy_id']) || !isset($_GET['schedule_id']))
{
    die("Policy ID or Premium Schedule ID Missing");
}

$policy_id = intval($_GET['policy_id']);
$schedule_id = intval($_GET['schedule_id']);

// Make sure this application belongs to the logged-in user
$checkPolicy = mysqli_query($conn,"
SELECT id
FROM policy_applications
WHERE id='$policy_id'
AND user_id='$user_id'
");

if(mysqli_num_rows($checkPolicy)==0)
{
    die("Invalid Policy Application");
}

// Make sure the premium schedule belongs to the logged-in user
$checkSchedule = mysqli_query($conn,"
SELECT id
FROM premium_schedule
WHERE id='$schedule_id'
AND user_id='$user_id'
");

if(mysqli_num_rows($checkSchedule)==0)
{
    die("Invalid Premium Schedule");
}

function uploadFile($file,$folder)
{
    $allowed = array("jpg","jpeg","png","pdf");

    $filename = $_FILES[$file]['name'];
    $tmp = $_FILES[$file]['tmp_name'];
    $size = $_FILES[$file]['size'];

    if($filename=="")
    {
        return false;
    }

    $ext = strtolower(pathinfo($filename,PATHINFO_EXTENSION));

    if(!in_array($ext,$allowed))
    {
        die("<script>alert('Only JPG, JPEG, PNG and PDF files are allowed');history.back();</script>");
    }

    if($size > 5*1024*1024)
    {
        die("<script>alert('Maximum file size is 5MB');history.back();</script>");
    }

    $newname = time().rand(1000,9999).".".$ext;

    move_uploaded_file($tmp,"uploads/".$folder."/".$newname);

    return $newname;
}

if(isset($_POST['upload']))
{

    $aadhaar = uploadFile("aadhaar","aadhaar");

    $smartcard = uploadFile("smartcard","smartcard");

    $photo = uploadFile("photo","photo");

    $income = uploadFile("income","income");

    $address = uploadFile("address","address");

    mysqli_query($conn,"
    DELETE FROM documents
    WHERE policy_id='$policy_id'
    ");

    $sql="

    INSERT INTO documents

    (

    user_id,

    policy_id,

    aadhaar_file,

    smartcard_file,

    photo_file,

    income_certificate,

    address_proof

    )

    VALUES

    (

    '$user_id',

    '$policy_id',

    '$aadhaar',

    '$smartcard',

    '$photo',

    '$income',

    '$address'

    )

    ";

    if(mysqli_query($conn,$sql))
    {
        echo "

        <script>

        alert('Documents Uploaded Successfully. Please complete your premium payment.');

        window.location='payment.php?id=$schedule_id';

        </script>

        ";
    }
    else
    {
        echo mysqli_error($conn);
    }

}

?>

<div class="container mt-5 mb-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3 class="text-center">

Upload Policy Verification Documents

</h3>

</div>

<div class="card-body">

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">

<label class="form-label">

Aadhaar Card

</label>

<input
type="file"
name="aadhaar"
class="form-control"
accept=".jpg,.jpeg,.png,.pdf"
required>

</div>

<div class="mb-3">

<label class="form-label">

Smart Card

</label>

<input
type="file"
name="smartcard"
class="form-control"
accept=".jpg,.jpeg,.png,.pdf"
required>

</div>

<div class="mb-3">

<label class="form-label">

Passport Photo

</label>

<input
type="file"
name="photo"
class="form-control"
accept=".jpg,.jpeg,.png"
required>

</div>

<div class="mb-3">

<label class="form-label">

Income Certificate

</label>

<input
type="file"
name="income"
class="form-control"
accept=".jpg,.jpeg,.png,.pdf"
required>

</div>

<div class="mb-4">

<label class="form-label">

Address Proof

</label>

<input
type="file"
name="address"
class="form-control"
accept=".jpg,.jpeg,.png,.pdf"
required>

</div>

<div class="text-center">

<button
type="submit"
name="upload"
class="btn btn-success btn-lg">

Upload Documents

</button>

<a
href="dashboard.php"
class="btn btn-secondary btn-lg">

Cancel

</a>

</div>

</form>

</div>

</div>

</div>

<?php include("includes/footer.php"); ?>