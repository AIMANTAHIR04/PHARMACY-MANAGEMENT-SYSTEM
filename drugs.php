<?php
require_once "config.php";
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Add Drug
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_drug"])) {
    $name = trim($_POST["drug_name"]);
    $desc = trim($_POST["description"]);
    $supplier = trim($_POST["supplier"]);
    $quantity = intval($_POST["quantity"]);
    $cost = floatval($_POST["cost"]);
    $date_supplied = $_POST["date_supplied"];
    $availability = $quantity > 0 ? "yes" : "no";

    $sql_add = "INSERT INTO stock (drug_name, date_supplied, description, supplier, quantity, cost, availability) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql_add)) {
        mysqli_stmt_bind_param($stmt, "ssssiis", $name, $date_supplied, $desc, $supplier, $quantity, $cost, $availability);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Delete Drug
if (isset($_GET["delete"])) {
    $stock_id = intval($_GET["delete"]);
    $sql_delete = "DELETE FROM stock WHERE stock_id = ?";
    if ($stmt = mysqli_prepare($link, $sql_delete)) {
        mysqli_stmt_bind_param($stmt, "i", $stock_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Drug Inventory</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style>
        body {
            font: 14px sans-serif;
            background-color: #e6f7ff;
            padding: 20px;
        }
        .wrapper {
            width: 95%;
            max-width: 1300px;
            margin: auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .page-header {
            text-align: center;
            color: #0066cc;
            margin-bottom: 30px;
        }
        .form-inline input, .form-inline select {
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .btn-delete {
            background-color: #cc0000;
            color: white;
            padding: 5px 10px;
            border: none;
        }
        .btn-delete:hover {
            background-color: #990000;
        }
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        th, td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #00AEEA;
            color: white;
            text-align: center;
        }
        td {
            text-align: center;
        }
        .btn-back {
            margin-top: 20px;
            background-color: #00AEEA;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
        }
        .btn-back:hover {
            background-color: #0088cc;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="page-header">
            <h1>Drug Inventory Management</h1>
        </div>

        <!-- Add Drug Form -->
        <form method="post" class="form-inline">
            <input type="text" name="drug_name" placeholder="Drug Name" required class="form-control">
            <input type="date" name="date_supplied" required class="form-control">
            <input type="text" name="description" placeholder="Description" required class="form-control">
            <input type="text" name="supplier" placeholder="Supplier" required class="form-control">
            <input type="number" name="quantity" placeholder="Quantity" min="0" required class="form-control">
            <input type="number" step="0.01" name="cost" placeholder="Cost" required class="form-control">
            <input type="submit" name="add_drug" value="Add Drug" class="btn btn-success">
        </form>

        <div class="table-container">
            <?php
            $sql = "SELECT * FROM stock ORDER BY stock_id DESC";
            if ($res = mysqli_query($link, $sql)) {
                if (mysqli_num_rows($res) > 0) {
                    echo "<table>";
                    echo "<thead><tr>
                            <th>ID</th>
                            <th>Drug Name</th>
                            <th>Date Supplied</th>
                            <th>Description</th>
                            <th>Supplier</th>
                            <th>Quantity</th>
                            <th>Cost</th>
                            <th>Availability</th>
                            <th>Action</th>
                          </tr></thead><tbody>";

                    while ($row = mysqli_fetch_array($res)) {
                        echo "<tr>";
                        echo "<td>" . $row['stock_id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['drug_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['date_supplied']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['supplier']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                        echo "<td>Rs. " . number_format($row['cost'], 2) . "</td>";
                        echo "<td><span class='label " . ($row['availability'] == 'yes' ? 'label-success' : 'label-danger') . "'>" . htmlspecialchars($row['availability']) . "</span></td>";
                        echo "<td><a href='drugs.php?delete=" . $row['stock_id'] . "' class='btn btn-delete' onclick='return confirm(\"Are you sure you want to delete this drug?\")'>Delete</a></td>";
                        echo "</tr>";
                    }

                    echo "</tbody></table>";
                } else {
                    echo "<div class='alert alert-info text-center'>No drugs in inventory.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>ERROR: Could not fetch data. " . htmlspecialchars(mysqli_error($link)) . "</div>";
            }

            mysqli_close($link);
            ?>
        </div>

        <a href="welcome.php" class="btn btn-primary btn-back">GO BACK TO DASHBOARD</a>
    </div>
</body>
</html>
