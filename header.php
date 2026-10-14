<?php
if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}

if(!isset($_SESSION['admin']))
{
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Admin Panel</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<link rel="stylesheet" href="../css/style.css">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

<div class="container">

<a class="navbar-brand" href="dashboard.php">
<i class="fa-solid fa-user-shield"></i> Admin Panel
</a>

<div class="navbar-nav ms-auto">

<a class="nav-link" href="dashboard.php">Dashboard</a>

<a class="nav-link" href="users.php">Users</a>

<a class="nav-link" href="policies.php">Policies</a>

<a class="nav-link" href="payments.php">Payments</a>

<a href="premium_management.php">Premium Management</a>

<a class="nav-link" href="contact_messages.php">Messages</a>

<a class="nav-link text-danger" href="logout.php">Logout</a>

</div>

</div>

</nav>

<div class="container mt-4">