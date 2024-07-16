<?php
include("conn.php");

if (isset($_POST['register'])) {
    if ($_POST['club_password'] != $_POST['club_cpassword']) {
        echo "<p>Please enter the same password</p>";
    } else {
        $cue_clubname = mysqli_real_escape_string($conn, $_POST['clubname']);
        $cue_fullname = mysqli_real_escape_string($conn, $_POST['club_fullname']);
        $cue_email = mysqli_real_escape_string($conn, $_POST['club_email']);
        $cue_mobile = mysqli_real_escape_string($conn, $_POST['club_mobile']);
        $cue_password = mysqli_real_escape_string($conn, $_POST['club_password']);
        $cue_cpassword = mysqli_real_escape_string($conn, $_POST['club_cpassword']);


// SQL query to check for existing username
$sql_registered = "SELECT `club_email` FROM `club_user` WHERE `club_email` = '{$cue_email}'";
$result_registered = mysqli_query($conn, $sql_registered) or die("Query Failed");

// Condition to check for existing username
if (mysqli_num_rows($result_registered) > 0) {
    echo "<p style='color: red; text-align: center; margin: 10px 0;'>Username already exists</p>";
} else {
    // Insert new user
    $sql_register = "INSERT INTO `club_user` (`clubname`, `club_fullname`, `club_email`, `club_mobile`, `club_password`, `club_cpassword`) VALUES ('{$cue_clubname}', '{$cue_fullname}', '{$cue_email}', '{$cue_mobile}', '{$cue_password}', '{$cue_cpassword}')";
    $result_register = mysqli_query($conn, $sql_register);

    if ($result_register) {
        // SQL query to create customer table
        $sql_create_table = "CREATE TABLE {$cue_clubname}_customer (
            customer_id INT AUTO_INCREMENT PRIMARY KEY,
            customer_name VARCHAR(225),
            customer_mobile_no VARCHAR(225),
            customer_price VARCHAR(225),
            customer_check_in_time DATETIME,
            customer_check_out_time DATETIME
        );";
        
        $result_customer_table = mysqli_query($conn, $sql_create_table);
        
        if ($result_customer_table) {
            echo "<p style='color: green; text-align: center; margin: 10px 0;'>User registered and table created successfully</p>";
        } else {
            echo "<p style='color: red; text-align: center; margin: 10px 0;'>Error creating customer table: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p style='color: red; text-align: center; margin: 10px 0;'>Error registering user: " . mysqli_error($conn) . "</p>";
    }
}
            // Corrected redirection
            header("Location: login.php");
            exit();
        }
    }


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cuebook.css">
    <title>Register</title>
</head>
<body class="register-body">
<!-- Nav Start -->
<nav>
    <div id="logo-pic">
        <img src="./assets/logo/svg/logo-no-background.svg" alt="threads" width="180" height="60">
    </div>
    
    <div>
        <ul id="nav-links">
            <li><a href="register.php">Create an Account</a></li>
            <li><a href="login.php">Log in</a></li>
        </ul>
    </div>

    <div id="menu" onclick="openMenu()">&#9776;</div>
</nav>

<div id="nav-col">
    <div id="nav-col-links" class="nav-col-links">
        <a id="link" href="register.php">Create an Account</a>
        <a id="link" href="login.php">Log in</a>
    </div>
</div>

<!-- Nav End -->

<div class="register-section">
    <div id="logo-pic">
        <img src="assets/logo/svg/logo-no-background.svg" alt="threads" width="180" height="60">
    </div>
    <h3>Create Your Account</h3>
    <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post">

        <div class="cue-input-field">
        <label for="">Club Name</label>
        <input type="text" name="clubname" id="clubname" placeholder="Your Club Name">
        </div>

        <div class="cue-input-field">
        <label for="">Full Name</label>
        <input type="text" name="club_fullname" id="club-full-name" placeholder="Enter Full Name">
        </div>

        <div class="cue-input-field">
        <label for="">Email</label>
        <input type="email" name="club_email" id="club-email" placeholder="Enter Email">
        </div>

        <div class="cue-input-field">
        <label for="">Mobile</label>
        <input type="text" name="club_mobile" id="club-mobile" placeholder="Enter Mobile number">
        </div>

        <div class="cue-input-field">
        <label for="">Password</label>
        <input type="password" name="club_password" id="club-password" placeholder="Enter Password">
        </div>

        <div class="cue-input-field">
        <label for="">Confirm Password</label>
        <input type="password" name="club_cpassword" id="club_cpassword" placeholder="Re-type Password">
        </div>
        <div class="cue-input-field" >
        <input type="submit" value="Submit" name="register" class="register-button">
        </div>
    </form>
    <p>Already have Account? <a href="login.php">Login</a></p>
</div>
<script src="app.js"></script>
</body>
</html>