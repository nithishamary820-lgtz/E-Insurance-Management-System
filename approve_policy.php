<?php
include("../includes/db.php");

if(isset($_GET['id']))
{
    $id = $_GET['id'];

    $sql = "UPDATE policy_applications
            SET status='Approved'
            WHERE id='$id'";

    if(mysqli_query($conn,$sql))
    {
        echo "<script>
        alert('Policy Approved Successfully');
        window.location='policies.php';
        </script>";
    }
    else
    {
        echo "Error: ".mysqli_error($conn);
    }
}
?>