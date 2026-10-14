<?php
include("../includes/db.php");

if(isset($_GET['id']))
{
    $id = $_GET['id'];

    $sql = "UPDATE policy_applications
            SET status='Rejected'
            WHERE id='$id'";

    if(mysqli_query($conn,$sql))
    {
        echo "<script>
        alert('Policy Rejected Successfully');
        window.location='policies.php';
        </script>";
    }
    else
    {
        echo "Error: ".mysqli_error($conn);
    }
}
?>