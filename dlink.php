<?php
// Include config file
require_once "config.php";
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$drug_name = isset($_SESSION['drug_name']) ? $_SESSION['drug_name'] : '';

$message = '';
if ($drug_name === '') {
    $message = '<div class="alert alert-warning">No drug name specified in the session.</div>';
} else {
    // Prepare a select statement
    $sql = "SELECT * FROM stock WHERE drug_name = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $drug_name);
        
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) == 0) {
                $message = '<div class="alert alert-danger">This drug is not available, SORRY.</div>';
            } else {
                $message = '<div class="alert alert-success">This drug is available! &#128077;</div>';
            }
        } else {
            $message = '<div class="alert alert-danger">Error executing query. Please try again later.</div>';
        }
        
        mysqli_stmt_close($stmt);
    } else {
        $message = '<div class="alert alert-danger">Failed to prepare the query.</div>';
    }
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AVAILABILITY</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style>
        body {
            font: 14px sans-serif;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .wrapper {
            max-width: 400px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .btn {
            width: 140px;
            margin: 10px 5px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Drug Availability</h2>
        <?php echo $message; ?>
        <p>
            <a href="process.php" class="btn btn-primary">PROCEED</a>
            <a href="welcome.php" class="btn btn-default">GO BACK</a>
        </p>
    </div>
</body>
</html>
