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

// Define variables and initialize with empty values
$cust_name = $Age = $sex = $address = $Phone = $date = "";
$cust_name_err = $Age_err = $sex_err = $address_err = $Phone_err = $date_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate cust_name
    if (empty(trim($_POST["cust_name"]))) {
        $cust_name_err = "Please enter a customer name.";
    } else {
        // Prepare a select statement to check if cust_name exists (optional)
        $sql = "SELECT pres_id FROM prescription WHERE cust_name = ?";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_cust_name);
            $param_cust_name = trim($_POST["cust_name"]);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // If you want to block duplicate cust_name, uncomment next line:
                    // $cust_name_err = "This customer name is already taken.";
                    // For now, allow duplicates, so no error here.
                    $cust_name = trim($_POST["cust_name"]);
                } else {
                    $cust_name = trim($_POST["cust_name"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Validate Age
    if (empty(trim($_POST["Age"]))) {
        $Age_err = "Please enter Age.";
    } elseif (!is_numeric($_POST["Age"]) || (int)$_POST["Age"] <= 0) {
        $Age_err = "Please enter a valid Age.";
    } else {
        $Age = (int)trim($_POST["Age"]);
    }

    // Validate sex
    if (empty(trim($_POST["sex"]))) {
        $sex_err = "Please select a sex.";
    } else {
        $sex = trim($_POST["sex"]);
    }

    // Validate address
    if (empty(trim($_POST["address"]))) {
        $address_err = "Please enter address.";
    } else {
        $address = trim($_POST["address"]);
    }

    // Validate Phone
    if (empty(trim($_POST["Phone"]))) {
        $Phone_err = "Please enter Phone.";
    } else {
        $Phone = trim($_POST["Phone"]);
    }

    // Validate date
    if (empty(trim($_POST["date"]))) {
        $date_err = "Please enter date.";
    } else {
        $date = trim($_POST["date"]);
    }

    // Store date in session if needed
    $_SESSION["date"] = $date;

    // Check input errors before inserting in database
    if (empty($cust_name_err) && empty($Age_err) && empty($sex_err) && empty($address_err) && empty($Phone_err) && empty($date_err)) {
        $sql = "INSERT INTO prescription (cust_name, Age, sex, address, Phone, date) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sissss", $cust_name, $Age, $sex, $address, $Phone, $date);

            if (mysqli_stmt_execute($stmt)) {
                // Data inserted successfully
                echo "<div class='alert alert-success'>Data successfully entered.</div>";
                // Optionally, clear form fields after success
                $cust_name = $Age = $sex = $address = $Phone = $date = "";
            } else {
                echo "<div class='alert alert-danger'>Something went wrong. Please try again later.</div>";
            }

            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DETAILS</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style>
        body {
            font: 14px 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
            padding: 40px;
        }
        .wrapper {
            width: 100%;
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .form-group label {
            font-weight: bold;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-block {
            width: 100%;
        }
        .help-block {
            color: red;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>DETAILS</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="form-group <?php echo (!empty($cust_name_err)) ? 'has-error' : ''; ?>">
                <label>Customer Name</label>
                <input type="text" name="cust_name" class="form-control" value="<?php echo htmlspecialchars($cust_name); ?>">
                <span class="help-block"><?php echo $cust_name_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($Age_err)) ? 'has-error' : ''; ?>">
                <label>Age</label>
                <input type="number" name="Age" class="form-control" value="<?php echo htmlspecialchars($Age); ?>">
                <span class="help-block"><?php echo $Age_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($sex_err)) ? 'has-error' : ''; ?>">
                <label>Sex</label>
                <select name="sex" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Male" <?php if ($sex == "Male") echo "selected"; ?>>Male</option>
                    <option value="Female" <?php if ($sex == "Female") echo "selected"; ?>>Female</option>
                    <option value="Other" <?php if ($sex == "Other") echo "selected"; ?>>Other</option>
                </select>
                <span class="help-block"><?php echo $sex_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                <label>Address</label>
                <textarea name="address" class="form-control"><?php echo htmlspecialchars($address); ?></textarea>
                <span class="help-block"><?php echo $address_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($Phone_err)) ? 'has-error' : ''; ?>">
                <label>Phone</label>
                <input type="text" name="Phone" class="form-control" value="<?php echo htmlspecialchars($Phone); ?>">
                <span class="help-block"><?php echo $Phone_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($date_err)) ? 'has-error' : ''; ?>">
                <label>Date</label>
                <input type="date" name="date" class="form-control" value="<?php echo htmlspecialchars($date); ?>">
                <span class="help-block"><?php echo $date_err; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary btn-block" value="Submit">
            </div>

            <div class="form-group">
                <a href="predetails.php" class="btn btn-success btn-block" role="button">Proceed</a>
            </div>

        </form>
    </div>
</body>
</html>
