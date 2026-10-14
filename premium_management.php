<?php
include("../includes/db.php");
include("header.php");

$sql = "SELECT
payments.id,
users.name,
insurance_plans.plan_name,
premium_schedule.premium_amount,
premium_schedule.due_date,
premium_schedule.status AS premium_status,
payments.payment_method,
payments.transaction_id,
payments.payment_date

FROM payments

INNER JOIN users
ON payments.user_id = users.id

INNER JOIN premium_schedule
ON payments.schedule_id = premium_schedule.id

INNER JOIN insurance_plans
ON premium_schedule.policy_id = insurance_plans.id

ORDER BY payments.id DESC";

$result = mysqli_query($conn,$sql);
?>

<div class="container">

<h2 align="center">Premium Management</h2>

<table border="1" width="100%" cellpadding="10">

<tr style="background:#003366;color:white;">

<th>ID</th>
<th>User</th>
<th>Policy</th>
<th>Amount</th>
<th>Due Date</th>
<th>Status</th>
<th>Payment Method</th>
<th>Transaction ID</th>
<th>Payment Date</th>

</tr>

<?php

if(mysqli_num_rows($result)>0)
{

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['plan_name']; ?></td>

<td>₹<?php echo $row['premium_amount']; ?></td>

<td><?php echo $row['due_date']; ?></td>

<td><?php echo $row['premium_status']; ?></td>

<td><?php echo $row['payment_method']; ?></td>

<td><?php echo $row['transaction_id']; ?></td>

<td><?php echo $row['payment_date']; ?></td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="9" align="center">

No Premium Payments Found

</td>

</tr>

<?php

}

?>

</table>

</div>

<?php include("../includes/footer.php"); ?>