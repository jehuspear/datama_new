<?php 
error_reporting(E_ALL &~  E_NOTICE);

$reservation_date = $_GET['date'];
$facility_ID = $_GET['facility'];
$userId = $_GET['id'];
$status = $_GET['status'];


require_once "database_user.php";
    $sql ="INSERT INTO tbl_reservation (FACILITY_ID, USER_ID, RESERVATION_DATE, STATUS_ID) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    $preparestmt = mysqli_stmt_prepare($stmt, $sql);
if ($preparestmt) {
    mysqli_stmt_bind_param($stmt, "ssss", $facility_ID, $userId, $reservation_date, $status);
    mysqli_stmt_execute($stmt);
    echo "<div class = 'alert alert-success'> DATE RESERVED </div>";
    header("Location: calendar.php?calendar.php?month=03&year=2024&id=$userid&facility=$facility_ID");
        exit();
} else {
    die("Something went wrong!");
}

?>