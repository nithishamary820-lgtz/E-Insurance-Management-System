<?php
include("includes/db.php");
include("includes/header.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id=$_SESSION['user_id'];

if(isset($_POST['send']))
{
    $message=mysqli_real_escape_string($conn,$_POST['message']);

    mysqli_query($conn,"INSERT INTO chat_messages(user_id,sender,message)
    VALUES('$user_id','user','$message')");
}

$result=mysqli_query($conn,"
SELECT *
FROM chat_messages
WHERE user_id='$user_id'
ORDER BY created_at ASC
");
?>

<div class="container mt-5">

<h2 class="text-center">Chat With Admin</h2>

<div class="border p-3 mb-3" style="height:400px;overflow-y:auto;">

<?php

while($row=mysqli_fetch_assoc($result))
{

if($row['sender']=="user")
{

echo "<div class='text-end mb-2'>
<span class='btn btn-primary'>".$row['message']."</span>
</div>";

}
else
{

echo "<div class='text-start mb-2'>
<span class='btn btn-success'>".$row['message']."</span>
</div>";

}

}

?>

</div>

<form method="POST">

<div class="input-group">

<input
type="text"
name="message"
class="form-control"
placeholder="Type Message..."
required>

<button
class="btn btn-primary"
name="send">

Send

</button>

</div>

</form>

</div>

<?php include("includes/footer.php"); ?>