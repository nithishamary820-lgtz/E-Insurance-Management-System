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

/* ===========================
   GET APPROVED POLICIES
=========================== */

$policies = mysqli_query($conn,"
SELECT
pa.id,
ip.plan_name

FROM policy_applications pa

INNER JOIN insurance_plans ip
ON pa.plan_id=ip.id

WHERE
pa.user_id='$user_id'
AND pa.status='Approved'

ORDER BY pa.id DESC
");

/* ===========================
   SUBMIT CLAIM
=========================== */

if(isset($_POST['submit']))
{
    $policy_id = intval($_POST['policy_id']);

    $claim_amount = mysqli_real_escape_string(
        $conn,
        $_POST['claim_amount']
    );

    $reason = mysqli_real_escape_string(
        $conn,
        $_POST['reason']
    );

    $document="";

    if(isset($_FILES['document']) &&
       $_FILES['document']['error']==0)
    {

        $ext = strtolower(
        pathinfo(
        $_FILES['document']['name'],
        PATHINFO_EXTENSION
        ));

        $allowed = array(
        "pdf",
        "jpg",
        "jpeg",
        "png"
        );

        if(in_array($ext,$allowed))
        {

            $document=time()."_".
            basename($_FILES['document']['name']);

            move_uploaded_file(
            $_FILES['document']['tmp_name'],
            "uploads/claims/".$document
            );

        }
        else
        {
            echo "<script>
            alert('Only PDF, JPG, JPEG & PNG files are allowed.');
            </script>";
        }

    }

    $check=mysqli_query($conn,"
    SELECT *
    FROM claims
    WHERE
    user_id='$user_id'
    AND policy_id='$policy_id'
    AND status='Pending'
    ");

    if(mysqli_num_rows($check)>0)
    {

        echo "<script>
        alert('A pending claim already exists for this policy.');
        </script>";

    }
    else
    {

        $sql="INSERT INTO claims
        (
        user_id,
        policy_id,
        claim_amount,
        reason,
        document,
        status
        )

        VALUES
        (
        '$user_id',
        '$policy_id',
        '$claim_amount',
        '$reason',
        '$document',
        'Pending'
        )";

        if(mysqli_query($conn,$sql))
        {

            echo "<script>

            alert('Claim Submitted Successfully');

            window.location='my_claims.php';

            </script>";

            exit();

        }
        else
        {

            echo "<script>

            alert('".mysqli_error($conn)."');

            </script>";

        }

    }

}
?>

<div class="container mt-5 mb-5">

<div class="card shadow">

<div class="card-header bg-danger text-white">

<h3>

Insurance Claim Request

</h3>

</div>

<div class="card-body">

<form
method="POST"
enctype="multipart/form-data">

<label>Select Approved Policy</label>

<select
name="policy_id"
class="form-control"
required>

<option value="">
-- Select Policy --
</option>

<?php

mysqli_data_seek($policies,0);

while($row=mysqli_fetch_assoc($policies))
{

?>

<option value="<?php echo $row['id']; ?>">

<?php echo htmlspecialchars($row['plan_name']); ?>

</option>

<?php

}

?>

</select>

<br>

<label>Claim Amount (₹)</label>

<input
type="number"
name="claim_amount"
class="form-control"
min="1"
required>

<br>

<label>Reason for Claim</label>

<textarea
name="reason"
class="form-control"
rows="5"
placeholder="Enter claim reason..."
required></textarea>

<br>

<label>Upload Supporting Document</label>

<input
type="file"
name="document"
class="form-control"
accept=".pdf,.jpg,.jpeg,.png"
required>

<small class="text-muted">

Allowed: PDF, JPG, JPEG, PNG

</small>

<br><br>

<div class="text-center">

<button
type="submit"
name="submit"
class="btn btn-danger">

Submit Claim

</button>

<a
href="dashboard.php"
class="btn btn-secondary">

Back

</a>

</div>

</form>

</div>

</div>

<hr>

<div class="alert alert-info mt-4">

<b>Note:</b>

<ul>

<li>Your claim will be reviewed by the administrator.</li>

<li>Only approved policies can be claimed.</li>

<li>Duplicate pending claims are not allowed.</li>

<li>Please upload valid supporting documents.</li>

</ul>

</div>

</div>

<?php include("includes/footer.php"); ?>