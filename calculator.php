<?php
include("includes/header.php");

$monthly = "";
$yearly = "";

if(isset($_POST['calculate']))
{
    $age = $_POST['age'];
    $sum = $_POST['sum_assured'];
    $term = $_POST['term'];

    // Simple Premium Formula
    $yearly = ($sum / $term) + ($age * 10);
    $monthly = round($yearly / 12);
}
?>

<div class="container">

<h2 align="center">Premium Calculator</h2>

<form method="POST">

<label>Age</label>
<input type="number" name="age" required>

<label>Sum Assured (₹)</label>
<input type="number" name="sum_assured" required>

<label>Policy Term (Years)</label>
<input type="number" name="term" required>

<br><br>

<input type="submit" name="calculate" value="Calculate Premium" class="btn">

</form>

<?php if($monthly!=""){ ?>

<hr>

<h3>Calculation Result</h3>

<p><b>Monthly Premium:</b> ₹<?php echo $monthly; ?></p>

<p><b>Yearly Premium:</b> ₹<?php echo $yearly; ?></p>

<?php } ?>

</div>

<?php include("includes/footer.php"); ?>