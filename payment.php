<?php

session_start();

include("includes/db.php");
include("includes/header.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id=$_SESSION['user_id'];

if(!isset($_GET['id']))
{
    echo "<script>
    alert('Invalid Premium Schedule');
    window.location='premium_due.php';
    </script>";
    exit();
}

$schedule_id=(int)$_GET['id'];

$get=mysqli_query($conn,"
SELECT
premium_schedule.*,
insurance_plans.plan_name

FROM premium_schedule

INNER JOIN insurance_plans
ON premium_schedule.policy_id=insurance_plans.id

WHERE premium_schedule.id='$schedule_id'
AND premium_schedule.user_id='$user_id'
");

if(mysqli_num_rows($get)==0)
{
    echo "<script>
    alert('Premium Record Not Found');
    window.location='premium_due.php';
    </script>";
    exit();
}

$row=mysqli_fetch_assoc($get);

if($row['status']=="Paid")
{
    echo "<script>
    alert('This Premium is Already Paid');
    window.location='premium_due.php';
    </script>";
    exit();
}

if(isset($_POST['pay']))
{

$amount=$row['premium_amount'];

$payment_method=mysqli_real_escape_string(
$conn,
$_POST['payment_method']
);

$transaction_id="TXN".date("YmdHis").rand(1000,9999);

$sql="INSERT INTO payments
(
schedule_id,
user_id,
amount,
payment_method,
transaction_id,
payment_status
)

VALUES
(
'$schedule_id',
'$user_id',
'$amount',
'$payment_method',
'$transaction_id',
'Success'
)";
if(mysqli_query($conn,$sql))
{

    mysqli_query($conn,"
    UPDATE premium_schedule
    SET status='Paid'
    WHERE id='$schedule_id'
    ");

    mysqli_query($conn,"
    INSERT INTO notifications
    (
        user_id,
        title,
        message
    )
    VALUES
    (
        '$user_id',
        'Premium Payment Successful',
        'Your premium payment of ₹$amount for ".$row['plan_name']." has been received successfully.'
    )
    ");

    echo "<script>
    alert('Payment Successful');
    window.location='download_receipt.php?transaction_id=$transaction_id';
    </script>";

    exit();

}
else
{

    echo "<div class='alert alert-danger mt-3'>";

    echo mysqli_error($conn);

    echo "</div>";

}

}

?>

<div class="container mt-5 mb-5">

<div class="card shadow">

<div class="card-header bg-success text-white">

<h3 class="text-center">

💳 Premium Payment

</h3>

</div>

<div class="card-body">

<div class="alert alert-info">

<h5>

Insurance Plan :
<b><?php echo htmlspecialchars($row['plan_name']); ?></b>

</h5>

<h5>

Premium Amount :
₹ <?php echo number_format($row['premium_amount']); ?>

</h5>

<h5>

Due Date :
<?php echo htmlspecialchars($row['due_date']); ?>

</h5>

</div>

<form method="POST">

<div class="mb-3">

<label class="form-label">

Payment Method

</label>

<select
name="payment_method"
class="form-control"
required>

<option value="">Select Payment Method</option>

<option value="UPI">UPI</option>

<option value="Credit Card">Credit Card</option>

<option value="Debit Card">Debit Card</option>

<option value="Net Banking">Net Banking</option>

</select>

</div>

<div class="mb-3">

<label class="form-label">

Card / UPI Number

</label>

<input
type="text"
class="form-control"
placeholder="Demo Payment Only"
required>

</div>

<div class="mb-3">

<label class="form-label">

Card Holder Name

</label>

<input
type="text"
class="form-control"
placeholder="Enter Name"
required>

</div>

<div class="mb-3">

<label class="form-label">

CVV

</label>

<input
type="password"
maxlength="3"
class="form-control"
placeholder="***"
required>

</div>

<div class="mb-3">

<label class="form-label">

Expiry Date

</label>

<input
type="month"
class="form-control"
required>

</div>

<div class="text-center">

<button
type="submit"
name="pay"
class="btn btn-success btn-lg">

💳 Pay ₹<?php echo number_format($row['premium_amount']); ?>

</button>

<a
href="premium_due.php"
class="btn btn-secondary btn-lg">

Cancel

</a>

</div>

</form>

</div>

</div>

<?php include("includes/footer.php"); ?>