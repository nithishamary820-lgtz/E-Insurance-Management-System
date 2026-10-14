<?php

session_start();

require("fpdf/fpdf.php");

include("includes/db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id=$_SESSION['user_id'];

$sql="

SELECT

policy_applications.id,

policy_applications.created_at,

users.name,
users.email,
users.phone,

insurance_plans.plan_name,
insurance_plans.description,
insurance_plans.premium

FROM policy_applications

INNER JOIN users
ON policy_applications.user_id=users.id

INNER JOIN insurance_plans
ON policy_applications.plan_id=insurance_plans.id

WHERE

policy_applications.user_id='$user_id'

AND

policy_applications.status='Approved'

ORDER BY policy_applications.id DESC

LIMIT 1

";

$result=mysqli_query($conn,$sql);

if(mysqli_num_rows($result)==0)
{
die("No Approved Policy Found");
}

$row=mysqli_fetch_assoc($result);

$pdf=new FPDF();

$pdf->AddPage();

$pdf->SetTitle("Insurance Policy Certificate");

$pdf->SetFont("Arial","B",20);

$pdf->Cell(190,12,"INSURANCE POLICY CERTIFICATE",0,1,"C");

$pdf->Ln(8);

$pdf->SetFont("Arial","",12);

$pdf->Cell(60,10,"Certificate No");
$pdf->Cell(5,10,":");
$pdf->Cell(100,10,"CERT-".$row['id'],0,1);

$pdf->Cell(60,10,"Policy ID");
$pdf->Cell(5,10,":");
$pdf->Cell(100,10,$row['id'],0,1);

$pdf->Cell(60,10,"Policy Holder");
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

$pdf->Cell(60,10,"Premium");
$pdf->Cell(5,10,":");
$pdf->Cell(100,10,"Rs. ".number_format($row['premium']),0,1);

$pdf->Cell(60,10,"Issued Date");
$pdf->Cell(5,10,":");

if(isset($row['created_at']))
{
    $pdf->Cell(100,10,$row['created_at'],0,1);
}
else
{
    $pdf->Cell(100,10,date("Y-m-d"),0,1);
}

$pdf->Ln(10);

$pdf->SetFont("Arial","B",13);

$pdf->Cell(190,10,"Policy Description",0,1);

$pdf->SetFont("Arial","",11);

$pdf->MultiCell(
190,
8,
$row['description']
);

$pdf->Ln(10);

$pdf->MultiCell(
190,
8,
"This certificate confirms that the above policy holder has successfully enrolled in the selected insurance policy. This certificate is issued electronically and is valid without a physical signature."
);

$pdf->Ln(20);

$pdf->Cell(120);

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
    "D",
    "Policy_Certificate_".$row['id'].".pdf"
);
exit();

?>