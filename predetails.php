<?php
require_once "config.php";
session_start();

// Redirect if not logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Initialize variables
$cust_name = $drug_name = $condition_ = $dose = $quantity = $doctor_name = "";
$cust_name_err = $drug_name_err = $condition__err = $dose_err = $quantity_err = $doctor_name_err = "";
$success_msg = "";

// Handle form submission
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate Customer Name
    if(empty(trim($_POST["cust_name"]))){
        $cust_name_err = "Please enter a customer name.";
    } else{
        $sql = "SELECT pres_id FROM prescription_detail WHERE cust_name = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_cust_name);
            $param_cust_name = trim($_POST["cust_name"]);
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $cust_name_err = "This customer already has a prescription.";
                } else{
                    $cust_name = $param_cust_name;
                }
            } else{
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Validate other inputs
    $drug_name = trim($_POST["drug_name"]);
    $condition_ = trim($_POST["condition_"]);
    $dose = trim($_POST["dose"]);
    $quantity = trim($_POST["quantity"]);
    $doctor_name = trim($_POST["doctor_name"]);

    if(empty($drug_name)) $drug_name_err = "Please enter a drug name.";
    if(empty($condition_)) $condition__err = "Please enter a condition.";
    if(empty($dose)) $dose_err = "Please enter a dose.";
    if(empty($quantity)) $quantity_err = "Please enter a quantity.";
    if(empty($doctor_name)) $doctor_name_err = "Please enter the doctor's name.";

    // Store in session
    $_SESSION["cust_name"] = $cust_name;
    $_SESSION["drug_name"] = $drug_name;
    $_SESSION["quantity"] = $quantity;
    $_SESSION["doctor_name"] = $doctor_name;

    // Insert into DB if no errors
    if(empty($cust_name_err) && empty($drug_name_err) && empty($condition__err) && empty($dose_err) && empty($quantity_err) && empty($doctor_name_err)){
        $stmt = mysqli_stmt_init($link);
        if(mysqli_stmt_prepare($stmt, "INSERT INTO prescription_detail (cust_name, drug_name, condition_, dose, quantity, doctor_name) VALUES (?, ?, ?, ?, ?, ?)")){
            mysqli_stmt_bind_param($stmt, "ssssss", $cust_name, $drug_name, $condition_, $dose, $quantity, $doctor_name);
            if(mysqli_stmt_execute($stmt)){
                $success_msg = "Prescription details successfully entered.";
                // Clear input fields
                $cust_name = $drug_name = $condition_ = $dose = $quantity = $doctor_name = "";
            } else{
                echo "Something went wrong. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prescription Detail</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style>
        body { font: 14px sans-serif; }
        .wrapper { width: 400px; padding: 20px; margin: auto; }
        .success-msg { color: green; font-weight: bold; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Prescription Details</h2>
        <p>Fill in the form below with the prescription details.</p>

        <?php if(!empty($success_msg)): ?>
            <p class="success-msg"><?php echo $success_msg; ?></p>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($cust_name_err)) ? 'has-error' : ''; ?>">
                <label>Customer Name</label>
                <input type="text" name="cust_name" class="form-control" value="<?php echo htmlspecialchars($cust_name); ?>">
                <span class="help-block"><?php echo $cust_name_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($drug_name_err)) ? 'has-error' : ''; ?>">
                <label>Drug Name</label>
                <input type="text" name="drug_name" class="form-control" value="<?php echo htmlspecialchars($drug_name); ?>">
                <span class="help-block"><?php echo $drug_name_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($condition__err)) ? 'has-error' : ''; ?>">
                <label>Condition</label>
                <input type="text" name="condition_" class="form-control" value="<?php echo htmlspecialchars($condition_); ?>">
                <span class="help-block"><?php echo $condition__err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($dose_err)) ? 'has-error' : ''; ?>">
                <label>Dose</label>
                <input type="text" name="dose" class="form-control" value="<?php echo htmlspecialchars($dose); ?>">
                <span class="help-block"><?php echo $dose_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($quantity_err)) ? 'has-error' : ''; ?>">
                <label>Quantity</label>
                <input type="text" name="quantity" class="form-control" value="<?php echo htmlspecialchars($quantity); ?>">
                <span class="help-block"><?php echo $quantity_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($doctor_name_err)) ? 'has-error' : ''; ?>">
                <label>Doctor Name</label>
                <input type="text" name="doctor_name" class="form-control" value="<?php echo htmlspecialchars($doctor_name); ?>">
                <span class="help-block"><?php echo $doctor_name_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p><a href="dlink.php">Proceed</a></p>
        </form>
    </div>    
</body>
</html>
