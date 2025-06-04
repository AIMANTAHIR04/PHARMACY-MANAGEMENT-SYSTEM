<?php
// Include your DB connection
require_once "config.php";

// Run JOIN query
$sql = "SELECT 
            p.pres_id,
            p.cust_name,
            r.drug,
            r.quantity,
            r.total,
            r.date AS receipt_date,
            r.doctor_name
        FROM 
            prescription p
        JOIN 
            receipts r ON p.pres_id = r.pres_id";

$result = mysqli_query($link, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prescription Receipts</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body { padding: 20px; background-color: #f8f9fa; font-family: Arial; }
        .container { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #007BFF; margin-bottom: 25px; }
        table { margin-top: 15px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Prescription Receipt Report</h2>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Prescription ID</th>
                    <th>Customer Name</th>
                    <th>Drug</th>
                    <th>Quantity</th>
                    <th>Total (Rs)</th>
                    <th>Receipt Date</th>
                    <th>Doctor Name</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['pres_id']) ?></td>
                        <td><?= htmlspecialchars($row['cust_name']) ?></td>
                        <td><?= htmlspecialchars($row['drug']) ?></td>
                        <td><?= htmlspecialchars($row['quantity']) ?></td>
                        <td><?= htmlspecialchars($row['total']) ?></td>
                        <td><?= htmlspecialchars($row['receipt_date']) ?></td>
                        <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">No matching records found.</div>
    <?php endif; ?>

    <a href="welcome.php" class="btn btn-primary">Back to Dashboard</a>
</div>
</body>
</html>

<?php
mysqli_close($link);
?>
