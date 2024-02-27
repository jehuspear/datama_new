<?php
session_start();
if (isset($_SESSION["employee"])) {
    header("Location: employee_dashboard.php");
}

if (isset($_POST["login"])) {
    $Email = $_POST["Email"];
    $password = $_POST["password"];
    require_once "database_employee.php";
    $sql = "SELECT * FROM tbl_employee WHERE EMAIL_ADD = '$Email'";
    $result = mysqli_query($conn, $sql);
    $employee = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if ($employee) {
        if (password_verify($password, $employee["PASSWORD"])) {
            $_SESSION["employee_id"] = $employee["EMPLOYEE_ID"];
            $_SESSION["employee"] = "yes";
            
            // Regenerate session ID for security
            session_regenerate_id(true);

            // Debugging statements
            echo "Session ID: " . session_id() . "<br>";
            echo "Employee ID: " . $_SESSION["employee_id"] . "<br>";

            header("Location: employee_dashboard.php");
            die();
        }
    } else {
        echo "<div class='alert alert-danger'>Email does not match</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="login_employee.css">

</head>
<body>
    <div class="container">       
        <header>
            <h1>Employee Login</h1>
        </header>
        <?php
            if (isset($_POST["login"])) {
                $Email = $_POST["Email"];
                $password = $_POST["password"];
                require_once "database_employee.php";
                $sql = "SELECT * FROM tbl_employee WHERE EMAIL_ADD = '$Email'";
                $result = mysqli_query($conn, $sql);
                $employee = mysqli_fetch_array($result, MYSQLI_ASSOC);
                if ($employee) {
                    if (password_verify($password, $employee["PASSWORD"])) {
                        $_SESSION["employee_id"] = $employee["EMPLOYEE_ID"];
                        $_SESSION["employee"] = "yes";
                        
                        // Regenerate session ID for security
                        session_regenerate_id(true);

                        header("Location: employee_dashboard.php");
                        die();
                    } else {
                        echo "<div class='alert alert-danger'>Incorrect password</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Email does not match</div>";
                }
            }
        ?>


        <form action="login_employee.php" method="post">
            <div class="form-group">
                <label for="email">Email: </label>
                <input type="email" name="Email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password">Password: </label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="form-btn">

                <input type="submit" value="Login" name="login" class="btn btn-primary">
            </div>
        </form>

        <div><p> Not Registered yet? <a href="registration_employee.php"> Register Here</a></div>


    </div>
    
</body>
</html>