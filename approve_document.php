<?php

include("../includes/db.php");

$id=$_GET['id'];

mysqli_query($conn,"
UPDATE users
SET document_status='Approved'
WHERE id='$id'
");

header("Location: users.php");

?>