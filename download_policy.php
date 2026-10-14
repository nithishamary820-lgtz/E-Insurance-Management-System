<?php
session_start();

require("fpdf/fpdf.php");
include("includes/db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if(!isset($_GET['id']))
{
    die("Invalid Policy");
}

$policy_id = intval($_GET['id']);

$sql = "
SELECT
policy_applications.*,
insurance_plans.plan_name,
insurance_plans.premium,
users.name,
users.email,
users.phone
FROM policy_applications
INNER JOIN insurance_plans
ON policy_applications.plan_id=insurance_plans.id
INNER JOIN users
ON users.id=policy_applications.user_id
WHERE policy_applications.id='$policy_id'
AND policy_applications.user_id='$user_id'
";

$result = mysqli_query($conn,$sql);

if(mysqli_num_rows($result)==0)
{
    die("Policy Not Found");
}

$row = mysqli_fetch_assoc($result);

if($row['status']!="Approved")
{
    die("Policy is not approved.");
}

$pdf = new FPDF();

$pdf->AddPage();

$pdf->SetFont("Arial","B",18);

$pdf->Cell(190,12,"INSURANCE POLICY CERTIFICATE",0,1,"C");

$pdf->Ln(8);

$pdf->SetFont("Arial","",12);

$pdf->Cell(60,10,"Policy ID");
$pdf->Cell(120,10,$row['id'],0,1);

$pdf->Cell(60,10,"Policy Name");
$pdf->Cell(120,10,$row['plan_name'],0,1);

$pdf->Cell(60,10,"Customer Name");
$pdf->Cell(120,10,$row['name'],0,1);

$pdf->Cell(60,10,"Email");
$pdf->Cell(120,10,$row['email'],0,1);

$pdf->Cell(60,10,"Phone");
$pdf->Cell(120,10,$row['phone'],0,1);

$pdf->Cell(60,10,"Premium");
$pdf->Cell(120,10,"Rs. ".$row['premium'],0,1);

$pdf->Cell(60,10,"Status");
$pdf->Cell(120,10,$row['status'],0,1);

$pdf->Ln(15);

$pdf->SetFont("Arial","I",11);

$pdf->MultiCell(
190,
8,
"This certificate confirms that the above insurance policy has been successfully approved by the Insurance Management System."
);

$pdf->Ln(20);

$pdf->Cell(120);

$pdf->Cell(60,10,"Authorized Signature",0,1);

$pdf->Ln(15);

$pdf->SetFont("Arial","B",11);

$pdf->Cell(
190,
10,
"Generated On : ".date("d-m-Y H:i:s"),
0,
1,
"C"
);

$pdf->Output(
"I",
"Insurance_Policy_".$row['id'].".pdf"
);

exit();