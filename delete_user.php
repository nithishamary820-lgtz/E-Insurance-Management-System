<?php

include("../includes/db.php");


if(isset($_GET['id']))
{

$id = $_GET['id'];


// Delete user 

$sql = "DELETE FROM users WHERE id='$id'";


if(mysqli_query($conn,$sql))
{

echo "

<script>

alert('User Deleted Successfully');

window.location='users.php';

</script>

";

}

else
{

echo "Delete Failed";

}


}

?>