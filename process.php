<?php
require_once "config.php";
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Handle adding a new receipt
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_receipt"])) {
    $r_no = $_POST["r_no"];
    $pres_id = $_POST["pres_id"];
    $drug = $_POST["drug"];
    $quantity = $_POST["quantity"];
    $total = $_POST["total"];
    $pay_type = $_POST["pay_type"];
    $date = $_POST["date"];
    $doctor_name = $_POST["doctor_name"];

    $sql = "INSERT INTO receipts (r_no, pres_id, drug, quantity, total, pay_type, date, doctor_name) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssssidss", $r_no, $pres_id, $drug, $quantity, $total, $pay_type, $date, $doctor_name);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $success_msg = "Receipt added successfully!";
    } else {
        $error_msg = "Error adding receipt: " . mysqli_error($link);
    }
}

// Handle updating an existing receipt
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_receipt"])) {
    $r_no = $_POST["r_no"];
    $pres_id = $_POST["pres_id"];
    $drug = $_POST["drug"];
    $quantity = $_POST["quantity"];
    $total = $_POST["total"];
    $pay_type = $_POST["pay_type"];
    $date = $_POST["date"];
    $doctor_name = $_POST["doctor_name"];

    $sql = "UPDATE receipts 
            SET pres_id=?, drug=?, quantity=?, total=?, pay_type=?, date=?, doctor_name=? 
            WHERE r_no=?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssidsss", $pres_id, $drug, $quantity, $total, $pay_type, $date, $doctor_name, $r_no);
        if (mysqli_stmt_execute($stmt)) {
            $success_msg = "Receipt updated successfully!";
        } else {
            $error_msg = "Execution failed: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $error_msg = "Prepare failed: " . mysqli_error($link);
    }
}

// Get the latest receipt
$sql = "SELECT * FROM receipts ORDER BY date DESC LIMIT 1";
$result = mysqli_query($link, $sql);
$receipt = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prescription Receipt</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 50px;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .form-title {
            margin-top: 40px;
            color: #007BFF;
        }
        .form-section {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Prescription Receipt</h2>

    <?php if (!empty($success_msg)): ?>
        <div class="alert alert-success"><?= $success_msg ?></div>
    <?php elseif (!empty($error_msg)): ?>
        <div class="alert alert-danger"><?= $error_msg ?></div>
    <?php endif; ?>

    <?php if ($receipt): ?>
        <table class='table table-bordered'>
            <thead>
                <tr>
                    <th>Receipt No</th>
                    <th>Prescription ID</th>
                    <th>Drug</th>
                    <th>Quantity</th>
                    <th>Total (Rs)</th>
                    <th>Payment</th>
                    <th>Date</th>
                    <th>Doctor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= htmlspecialchars($receipt['r_no']) ?></td>
                    <td><?= htmlspecialchars($receipt['pres_id']) ?></td>
                    <td><?= htmlspecialchars($receipt['drug']) ?></td>
                    <td><?= htmlspecialchars($receipt['quantity']) ?></td>
                    <td><?= htmlspecialchars($receipt['total']) ?></td>
                    <td><?= htmlspecialchars($receipt['pay_type']) ?></td>
                    <td><?= htmlspecialchars($receipt['date']) ?></td>
                    <td><?= htmlspecialchars($receipt['doctor_name']) ?></td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <div class='alert alert-warning'><strong>No prescription details found.</strong></div>
    <?php endif; ?>

    <a href="receipts.php" class="btn btn-primary">View All Receipts</a>

    <!-- Add Receipt Form -->
    <h3 class="form-title">Add New Receipt</h3>
    <form method="post" class="form-section">
        <input type="hidden" name="add_receipt" value="1">
        <div class="form-group"><label>Receipt No</label><input type="text" name="r_no" class="form-control" required></div>
        <div class="form-group"><label>Prescription ID</label><input type="text" name="pres_id" class="form-control" required></div>
        <div class="form-group"><label>Drug</label><input type="text" name="drug" class="form-control" required></div>
        <div class="form-group"><label>Quantity</label><input type="number" name="quantity" class="form-control" required></div>
        <div class="form-group"><label>Total (Rs)</label><input type="number" step="0.01" name="total" class="form-control" required></div>
        <div class="form-group"><label>Payment Type</label><input type="text" name="pay_type" class="form-control" required></div>
        <div class="form-group"><label>Date</label><input type="date" name="date" class="form-control" required></div>
        <div class="form-group"><label>Doctor Name</label><input type="text" name="doctor_name" class="form-control" required></div>
        <input type="submit" value="Add Receipt" class="btn btn-success">
    </form>

    <!-- Update Receipt Form -->
    <h3 class="form-title">Update Existing Receipt</h3>
    <form method="post" class="form-section">
        <input type="hidden" name="update_receipt" value="1">
        <div class="form-group"><label>Receipt No (to update)</label><input type="text" name="r_no" class="form-control" required></div>
        <div class="form-group"><label>Prescription ID</label><input type="text" name="pres_id" class="form-control" required></div>
        <div class="form-group"><label>Drug</label><input type="text" name="drug" class="form-control" required></div>
        <div class="form-group"><label>Quantity</label><input type="number" name="quantity" class="form-control" required></div>
        <div class="form-group"><label>Total (Rs)</label><input type="number" step="0.01" name="total" class="form-control" required></div>
        <div class="form-group"><label>Payment Type</label><input type="text" name="pay_type" class="form-control" required></div>
        <div class="form-group"><label>Date</label><input type="date" name="date" class="form-control" required></div>
        <div class="form-group"><label>Doctor Name</label><input type="text" name="doctor_name" class="form-control" required></div>
        <input type="submit" value="Update Receipt" class="btn btn-warning">
    </form>
</div>
</body>
</html>

<?php mysqli_close($link); ?>
