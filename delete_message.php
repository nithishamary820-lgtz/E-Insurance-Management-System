<?php
include("../includes/db.php");

if(isset($_GET['id']))
{
    $id = (int)$_GET['id'];

    mysqli_query($conn,"DELETE FROM contact_messages WHERE id='$id'");

    header("Location: contact_messages.php");
    exit();
}
?>