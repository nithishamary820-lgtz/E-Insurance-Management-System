<?php

session_start();

if(!isset($_SESSION['admin']))
{
    header("Location: admin_login.php");
    exit();
}

include("../includes/db.php");

if(!isset($_GET['id']))
{
    die("Invalid Request");
}

$id = intval($_GET['id']);

/* ===========================
   Get Claim Details
=========================== */

$get = mysqli_query($conn,"
SELECT
claims.*,
insurance_plans.plan_name
FROM claims

INNER JOIN policy_applications
ON claims.policy_id=policy_applications.id

INNER JOIN insurance_plans
ON policy_applications.plan_id=insurance_plans.id

WHERE claims.id='$id'
");

if(mysqli_num_rows($get)==0)
{
    die("Claim Not Found");
}

$row = mysqli_fetch_assoc($get);

$user_id = $row['user_id'];

$plan_name = $row['plan_name'];

$claim_amount = $row['claim_amount'];

/* ===========================
   Approve Claim
=========================== */

mysqli_query($conn,"
UPDATE claims
SET status='Approved'
WHERE id='$id'
");

/* ===========================
   Send Notification
=========================== */

$title = "Claim Approved";

$message = "Congratulations! Your insurance claim for '".$plan_name."' has been approved successfully. Claim Amount: ₹".$claim_amount.".";

mysqli_query($conn,"
INSERT INTO notifications
(
user_id,
title,
message
)
VALUES
(
'$user_id',
'$title',
'$message'
)
");

/* ===========================
   Redirect
=========================== */

echo "<script>

alert('Claim Approved Successfully');

window.location='claims.php';

</script>";

exit();

?>