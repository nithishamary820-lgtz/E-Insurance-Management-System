<?php
include("../includes/db.php");
include("header.php");

$sql="SELECT DISTINCT users.id,users.name
FROM users
INNER JOIN chat_messages
ON users.id=chat_messages.user_id";

$result=mysqli_query($conn,$sql);

?>

<div class="container mt-5">

<h2 align="center">
Customer Chats
</h2>

<table class="table table-bordered">

<tr>

<th>User ID</th>

<th>Name</th>

<th>Action</th>

</tr>

<?php

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo htmlspecialchars($row['name']); ?></td>

<td>

<a
href="chat.php?user_id=<?php echo $row['id'];?>"
class="btn btn-primary">

Open Chat

</a>

</td>

</tr>

<?php

}

?>

</table>

</div>

<?php include("../includes/footer.php"); ?>