<?php

session_start();

require('fpdf/fpdf.php');

include("includes/db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id=$_SESSION['user_id'];

if(isset($_GET['transaction_id']))
{

$transaction_id=mysqli_real_escape_string(
$conn,
$_GET['transaction_id']
);

$sql="

SELECT

payments.*,

premium_schedule.policy_id,

insurance_plans.plan_name,

users.name,
users.email,
users.phone

FROM payments

INNER JOIN premium_schedule
ON payments.schedule_id=premium_schedule.id

INNER JOIN insurance_plans
ON premium_schedule.policy_id=insurance_plans.id

INNER JOIN users
ON users.id=payments.user_id

WHERE

payments.transaction_id='$transaction_id'

AND

payments.user_id='$user_id'

LIMIT 1

";

}
else
{

$sql="

SELECT

payments.*,

premium_schedule.policy_id,

insurance_plans.plan_name,

users.name,
users.email,
users.phone

FROM payments

INNER JOIN premium_schedule
ON payments.schedule_id=premium_schedule.id

INNER JOIN insurance_plans
ON premium_schedule.policy_id=insurance_plans.id

INNER JOIN users
ON users.id=payments.user_id

WHERE payments.user_id='$user_id'

ORDER BY payments.id DESC

LIMIT 1

";

}

$result=mysqli_query($conn,$sql);

if(mysqli_num_rows($result)==0)
{
die("No Payment Found");
}

$row=mysqli_fetch_assoc($result);

$pdf=new FPDF();

$pdf->AddPage();

$pdf->SetTitle("Payment Receipt");

$pdf->SetFont("Arial","B",18);

$pdf->Cell(190,10,"INSURANCE PAYMENT RECEIPT",0,1,"C");

$pdf->Ln(5);

$pdf->SetFont("Arial","",12);

$pdf->Cell(60,10,"Receipt No");
$pdf->Cell(5,10,":");
$pdf->Cell(100,10,"REC-".$row['id'],0,1);

$pdf->Cell(60,10,"Transaction ID");
$pdf->Cell(5,10,":");
$pdf->Cell(100,10,$row['transaction_id'],0,1);

$pdf->Cell(60,10,"Customer Name");
$pdf->Cell(5,10,":");
$pdf->Cell(100,10,$row['name'],0,1);

$pdf->Cell(60,10,"Email");
$pdf->Cell(5,10,":");
$pdf->Cell(100,10,$row['email'],0,1);

$pdf->Cell(60,10,"Phone");
$pdf->Cell(5,10,":");
$pdf->Cell(100,10,$row['phone'],0,1);

$pdf->Cell(60,10,"Insurance Plan");
$pdf->Cell(5,10,":");
$pdf->Cell(100,10,$row['plan_name'],0,1);

$pdf->Cell(60,10,"Amount Paid");
$pdf->Cell(5,10,":");
$pdf->Cell(100,10,"Rs. ".number_format($row['amount']),0,1);

$pdf->Cell(60,10,"Payment Method");
$pdf->Cell(5,10,":");
$pdf->Cell(100,10,$row['payment_method'],0,1);

$pdf->Cell(60,10,"Payment Status");
$pdf->Cell(5,10,":");
$pdf->Cell(100,10,$row['payment_status'],0,1);

$pdf->Cell(60,10,"Payment Date");
$pdf->Cell(5,10,":");

if(isset($row['payment_date']))
{
    $pdf->Cell(100,10,$row['payment_date'],0,1);
}
elseif(isset($row['created_at']))
{
    $pdf->Cell(100,10,$row['created_at'],0,1);
}
else
{
    $pdf->Cell(100,10,date("Y-m-d H:i:s"),0,1);
}

$pdf->Ln(10);

$pdf->SetFont("Arial","B",14);

$pdf->Cell(190,10,"Thank You For Your Payment!",0,1,"C");

$pdf->Ln(5);

$pdf->SetFont("Arial","",11);

$pdf->MultiCell(
190,
8,
"This is a system-generated premium payment receipt. It serves as proof of payment for your insurance premium. Please keep this receipt for future reference."
);

$pdf->Ln(20);

$pdf->Cell(130);

$pdf->Cell(60,8,"Authorized Signature",0,1,"C");

$pdf->Ln(15);

$pdf->SetFont("Arial","I",10);

$pdf->Cell(
190,
8,
"Generated on : ".date("d-m-Y H:i:s"),
0,
1,
"C"
);

$pdf->Output(
"I",
"Payment_Receipt_".$row['transaction_id'].".pdf"
);

exit();
?>