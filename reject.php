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

/* Get Policy Details */

$get = mysqli_query($conn,"
SELECT
policy_applications.user_id,
insurance_plans.plan_name
FROM policy_applications
INNER JOIN insurance_plans
ON policy_applications.plan_id=insurance_plans.id
WHERE policy_applications.id='$id'
");

if(mysqli_num_rows($get)==0)
{
    die("Policy Not Found");
}

$data = mysqli_fetch_assoc($get);

$user_id = $data['user_id'];

$plan_name = $data['plan_name'];

/* Update Policy Status */

mysqli_query($conn,"
UPDATE policy_applications
SET status='Rejected'
WHERE id='$id'
");

/* Send Notification */

$title = "Policy Rejected";

$message = "Your ".$plan_name." insurance policy application has been rejected. Please contact the insurance office for more information or submit a new application if required.";

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

/* Redirect */

echo "<script>

alert('Policy Rejected Successfully');

window.location='applications.php';

</script>";

exit();

?>