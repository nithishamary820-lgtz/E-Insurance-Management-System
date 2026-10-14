<?php
include("includes/db.php");
include("includes/header.php");

if(isset($_POST['send']))
{
    $name=$_POST['name'];
    $email=$_POST['email'];
    $subject=$_POST['subject'];
    $message=$_POST['message'];

    $sql="INSERT INTO contact_messages(name,email,subject,message)
          VALUES('$name','$email','$subject','$message')";

    if(mysqli_query($conn,$sql))
    {
        echo "<script>
        alert('Message Sent Successfully');
        window.location='contact.php';
        </script>";
    }
}
?>

<div class="container">

<h2 align="center">Contact Us</h2>

<form method="POST">

<label>Name</label>
<input type="text" name="name" required>

<label>Email</label>
<input type="email" name="email" required>

<label>Subject</label>
<input type="text" name="subject" required>

<label>Message</label>
<textarea name="message" rows="5" required></textarea>

<br><br>

<input type="submit" name="send" value="Send Message" class="btn">

</form>

<hr>

<h3>Our Office</h3>

<p><b>Insurance Management System</b></p>

<p>📍 thoothukudi, Tamil Nadu</p>

<p>📞 +91 8754291635</p>

<p>📧 support@insurance.com</p>

</div>

<?php include("includes/footer.php"); ?>