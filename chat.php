<?php
include("../includes/db.php");
include("header.php");

if(!isset($_GET['user_id']))
{
    echo "<h3>No User Selected</h3>";
    exit();
}

$user_id = (int)$_GET['user_id'];

if(isset($_POST['send']))
{
    $message = mysqli_real_escape_string($conn,$_POST['message']);

    mysqli_query($conn,"INSERT INTO chat_messages(user_id,sender,message)
    VALUES('$user_id','admin','$message')");
}

$result = mysqli_query($conn,"
SELECT *
FROM chat_messages
WHERE user_id='$user_id'
ORDER BY created_at ASC
");

?>

<div class="container mt-5">

<h2 class="text-center">
Admin Chat Panel
</h2>

<div style="height:450px;
overflow-y:auto;
border:1px solid #ddd;
padding:20px;
background:#f8f9fa;">

<?php

while($row=mysqli_fetch_assoc($result))
{

if($row['sender']=="user")
{

?>

<div style="text-align:left;margin-bottom:15px;">

<span
style="
background:#0d6efd;
color:white;
padding:10px 15px;
border-radius:10px;
display:inline-block;">

👤 User :

<?php echo htmlspecialchars($row['message']); ?>

</span>

</div>

<?php

}
else
{

?>

<div style="text-align:right;margin-bottom:15px;">

<span
style="
background:#198754;
color:white;
padding:10px 15px;
border-radius:10px;
display:inline-block;">

👨‍💼 Admin :

<?php echo htmlspecialchars($row['message']); ?>

</span>

</div>

<?php

}

}

?>

</div>

<form method="POST" class="mt-3">

<div class="input-group">

<input
type="text"
name="message"
class="form-control"
placeholder="Type your reply..."
required>

<button
class="btn btn-success"
name="send">

Send Reply

</button>

</div>

</form>

</div>

<?php include("../includes/footer.php"); ?>