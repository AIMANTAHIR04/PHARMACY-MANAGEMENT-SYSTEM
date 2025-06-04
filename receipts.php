<?php
// Include config file
require_once "config.php";

// Initialize the session
session_start();

// Redirect if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Fetch all receipts
$sql = "SELECT * FROM receipts ORDER BY date DESC";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Receipts</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 40px;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            margin-top: 20px;
        }
        h2 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>All Receipts</h2>
    <?php
    if ($res = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($res) > 0) {
            echo "<table class='table table-striped table-bordered'>";
            echo "<thead><tr>";
            echo "<th>Receipt No</th>";
            echo "<th>Prescription ID</th>";
            echo "<th>Drug</th>";
            echo "<th>Quantity</th>";
            echo "<th>Total (Rs)</th>";
            echo "<th>Payment Type</th>";
            echo "<th>Served By</th>";
            echo "<th>Date</th>";
            echo "<th>Doctor Name</th>";
            echo "</tr></thead><tbody>";

            while ($row = mysqli_fetch_assoc($res)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['r_no']) . "</td>";
                echo "<td>" . htmlspecialchars($row['pres_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['drug']) . "</td>";
                echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                echo "<td>" . htmlspecialchars($row['total']) . "</td>";
                echo "<td>" . htmlspecialchars($row['pay_type']) . "</td>";
                echo "<td>" . htmlspecialchars($row['served_by']) . "</td>";
                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['doctor_name']) . "</td>";
                echo "</tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-info'>No receipts found.</div>";
        }
        mysqli_free_result($res);
    } else {
        echo "<div class='alert alert-danger'>ERROR: Could not execute query. " . mysqli_error($link) . "</div>";
    }

    mysqli_close($link);
    ?>

    <a href="welcome.php" class="btn btn-primary">Go Back</a>
</div>
</body>
</html>
