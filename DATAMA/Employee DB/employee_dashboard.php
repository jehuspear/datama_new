<?php
include 'database_employee.php';
session_start(); // Start the session

if (!isset($_SESSION["employee_id"])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login_employee.php");
    exit();
}

// Fetch the user's information from the database
$employee_id = $_SESSION["employee_id"];
$sql = "SELECT FIRST_NAME FROM tbl_employee WHERE EMPLOYEE_ID = ?";
$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $employee_id );
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $first_name);

    if (mysqli_stmt_fetch($stmt)) {
        // $first_name now contains the user's first name
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php if (isset($first_name)) : ?>
            <h1>Welcome, <?php echo $first_name; ?>!</h1>
        <?php else : ?>
            <h1>Welcome to Employee Dashboard</h1>
        <?php endif; ?>
        <a href="logout_employee.php" class="btn btn-warning">Logout</a>
</body>
</html>