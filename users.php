<?php
session_start();

if(!isset($_SESSION['admin']))
{
    header("Location: admin_login.php");
    exit();
}

include("../includes/db.php");
include("header.php");

/* ===========================
   DELETE USER
=========================== */

if(isset($_GET['delete']))
{
    $user_id = intval($_GET['delete']);
    $getDocs = mysqli_query($conn,"
SELECT *
FROM documents
WHERE user_id='$user_id'
");

while($doc = mysqli_fetch_assoc($getDocs))
{
    @unlink("../uploads/aadhaar/".$doc['aadhaar_file']);
    @unlink("../uploads/smartcard/".$doc['smartcard_file']);
    @unlink("../uploads/photo/".$doc['photo_file']);
    @unlink("../uploads/income/".$doc['income_certificate']);
    @unlink("../uploads/address/".$doc['address_proof']);
}
    mysqli_query($conn,"DELETE FROM documents WHERE user_id='$user_id'");
    mysqli_query($conn,"DELETE FROM policy_applications WHERE user_id='$user_id'");
    mysqli_query($conn,"DELETE FROM payments WHERE user_id='$user_id'");
    mysqli_query($conn,"DELETE FROM claims WHERE user_id='$user_id'");
    mysqli_query($conn,"DELETE FROM notifications WHERE user_id='$user_id'");
    mysqli_query($conn,"DELETE FROM users WHERE id='$user_id'");

    echo "<script>
    alert('User Deleted Successfully');
    window.location='users.php';
    </script>";
    exit();
}

/* ===========================
   APPROVE DOCUMENT
=========================== */

if(isset($_GET['approve']))
{
    $id = intval($_GET['approve']);

    mysqli_query($conn,"
    UPDATE users
    SET document_status='Approved'
    WHERE id='$id'
    ");

    mysqli_query($conn,"
    INSERT INTO notifications(user_id,title,message)
    VALUES
    (
        '$id',
        'Documents Approved',
        'Your uploaded documents have been approved successfully.'
    )
    ");

    echo "<script>
    alert('Documents Approved');
    window.location='users.php';
    </script>";
    exit();
}

/* ===========================
   REJECT DOCUMENT
=========================== */

if(isset($_GET['reject']))
{
    $id = intval($_GET['reject']);

    mysqli_query($conn,"
    UPDATE users
    SET document_status='Rejected'
    WHERE id='$id'
    ");

    mysqli_query($conn,"
    INSERT INTO notifications(user_id,title,message)
    VALUES
    (
        '$id',
        'Documents Rejected',
        'Your uploaded documents have been rejected. Please upload valid documents again.'
    )
    ");

    echo "<script>
    alert('Documents Rejected');
    window.location='users.php';
    </script>";
    exit();
}

/* ===========================
   SEARCH
=========================== */

$search="";

if(isset($_GET['search']))
{
    $search=mysqli_real_escape_string($conn,$_GET['search']);
}

/* ===========================
   FETCH USERS
=========================== */


/* ===========================
   FETCH USERS
=========================== */

$sql = "

SELECT
u.*,
d.aadhaar_file,
d.smartcard_file,
d.photo_file,
d.income_certificate,
d.address_proof

FROM users u

LEFT JOIN documents d
ON d.id =
(
    SELECT MAX(id)
    FROM documents
    WHERE user_id = u.id
)

WHERE
u.name LIKE '%$search%'
OR u.email LIKE '%$search%'
OR u.phone LIKE '%$search%'

ORDER BY u.id DESC

";

$result = mysqli_query($conn,$sql);

?>

<div class="container-fluid mt-4">

<div class="card shadow">

<div class="card-body">

<h2 class="text-center">
👥 Manage Users
</h2>

<hr>

<form method="GET">

<div class="row">

<div class="col-md-10">

<input
type="text"
name="search"
class="form-control"
placeholder="Search by Name / Email / Phone"
value="<?php echo htmlspecialchars($search); ?>">

</div>

<div class="col-md-2">

<button class="btn btn-primary w-100">
Search
</button>

</div>

</div>

</form>

<br>

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Phone</th>
<th>Email Status</th>
<th>Document Status</th>
<th>Education</th>
<th>Documents</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($result)>0)
{

while($row=mysqli_fetch_assoc($result))
{

?>
<tr>

<td><?php echo $row['id']; ?></td>

<td>
<b><?php echo htmlspecialchars($row['name']); ?></b>
</td>

<td>
<?php echo htmlspecialchars($row['email']); ?>
</td>

<td>
<?php echo htmlspecialchars($row['phone']); ?>
</td>

<td>

<?php

if(isset($row['email_verified']) && $row['email_verified']=="Yes")
{
    echo "<span class='badge bg-success'>Verified</span>";
}
else
{
    echo "<span class='badge bg-danger'>Not Verified</span>";
}

?>

</td>

<td>

<?php

$status = !empty($row['document_status'])
? $row['document_status']
: "Pending";

if($status=="Approved")
{
    echo "<span class='badge bg-success'>Approved</span>";
}
elseif($status=="Rejected")
{
    echo "<span class='badge bg-danger'>Rejected</span>";
}
elseif($status=="Not Submitted")
{
    echo "<span class='badge bg-secondary'>Not Submitted</span>";
}
else
{
    echo "<span class='badge bg-warning text-dark'>Pending</span>";
}

?>

</td>

<td>

<b>School</b><br>

<?php echo htmlspecialchars($row['school_name']); ?>

<br><br>

<b>College</b><br>

<?php echo htmlspecialchars($row['college_name']); ?>

<br><br>

<b>Course</b><br>

<?php echo htmlspecialchars($row['course']); ?>

</td>

<td>

<b>Aadhaar No</b><br>

<?php echo htmlspecialchars($row['aadhaar_number']); ?>

<br><br>

<b>Smart Card No</b><br>

<?php echo htmlspecialchars($row['smart_card_number']); ?>

<br><br>

<b>Address</b><br>

<?php echo nl2br(htmlspecialchars($row['address'])); ?>

<hr>

<?php

if(
empty($row['aadhaar_file']) &&
empty($row['smartcard_file']) &&
empty($row['photo_file']) &&
empty($row['income_certificate']) &&
empty($row['address_proof'])
)
{
?>

<div class="alert alert-warning p-2 text-center">

No Documents Uploaded

</div>

<?php
}
?>

<?php

if(!empty($row['aadhaar_file']))
{
?>

<a
href="../uploads/aadhaar/<?php echo $row['aadhaar_file']; ?>"
target="_blank"
class="btn btn-primary btn-sm mb-1 w-100">

📄 View Aadhaar

</a>

<?php
}

if(!empty($row['smartcard_file']))
{
?>

<a
href="../uploads/smartcard/<?php echo $row['smartcard_file']; ?>"
target="_blank"
class="btn btn-success btn-sm mb-1 w-100">

💳 View Smart Card

</a>

<?php
}

if(!empty($row['photo_file']))
{
?>

<a
href="../uploads/photo/<?php echo $row['photo_file']; ?>"
target="_blank"
class="btn btn-info btn-sm mb-1 w-100">

🖼 View Photo

</a>

<?php
}

if(!empty($row['income_certificate']))
{
?>

<a
href="../uploads/income/<?php echo $row['income_certificate']; ?>"
target="_blank"
class="btn btn-warning btn-sm mb-1 w-100">

💰 Income Certificate

</a>

<?php
}

if(!empty($row['address_proof']))
{
?>

<a
href="../uploads/address/<?php echo $row['address_proof']; ?>"
target="_blank"
class="btn btn-secondary btn-sm mb-2 w-100">

🏠 Address Proof

</a>

<?php
}

?>

</td>

<td>

<?php if($status=="Pending"){ ?>

<a
href="users.php?approve=<?php echo $row['id']; ?>"
class="btn btn-success btn-sm mb-1 w-100"
onclick="return confirm('Approve this user's documents?')">

✅ Approve

</a>

<a
href="users.php?reject=<?php echo $row['id']; ?>"
class="btn btn-warning btn-sm mb-1 w-100"
onclick="return confirm('Reject this user's documents?')">

❌ Reject

</a>

<?php } elseif($status=="Approved"){ ?>

<button class="btn btn-success btn-sm mb-1 w-100" disabled>
✅ Approved
</button>

<?php } elseif($status=="Rejected"){ ?>

<button class="btn btn-danger btn-sm mb-1 w-100" disabled>
❌ Rejected
</button>

<?php } elseif($status=="Not Submitted"){ ?>

<button class="btn btn-secondary btn-sm mb-1 w-100" disabled>
📄 Not Submitted
</button>

<?php } ?>

<a
href="users.php?delete=<?php echo $row['id']; ?>"
class="btn btn-danger btn-sm w-100"
onclick="return confirm('Delete this user permanently?')">

🗑 Delete

</a>

</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="9" class="text-center text-danger">

No Users Found

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php include("footer.php"); ?>