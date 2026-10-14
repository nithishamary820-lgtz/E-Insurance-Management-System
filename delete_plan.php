<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['admin']))
{
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'];

mysqli_query($conn,"DELETE FROM insurance_plans WHERE id='$id'");

echo "<script>
alert('Plan Deleted Successfully');
window.location='plans.php';
</script>";
?>