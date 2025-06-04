<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome | AIMAN'S Pharmacy</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: rgba(21, 90, 159, 0.55);
            padding-top: 50px;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: auto;
        }
        .page-header h1 {
            font-size: 28px;
            margin-bottom: 30px;
        }
        .list-group a {
            font-size: 18px;
            padding: 12px;
        }
        .btn-group {
            margin-top: 20px;
        }
        .btn {
            min-width: 180px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>Hi, <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>. Welcome to <strong>AIMAN'S Pharmacy</strong>.</h1>
        </div>

        <div class="list-group">
            <a href="enter.php" class="list-group-item">Enter Your Details</a>
            <a href="predetails.php" class="list-group-item">Prescription Details</a>
            <a href="drugs.php" class="list-group-item">Drugs</a>
            <a href="process.php" class="list-group-item">View Receipt</a>
            <a href="PRESCRIPTION_RECEIPT.php" class="list-group-item">Prescription Receipt Report</a> <!-- ✅ New Report Link -->
        </div>

        <div class="btn-group">
            <a href="reset.php" class="btn btn-warning">Reset Your Password</a>
            <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
        </div>
    </div>
</body>
</html>
