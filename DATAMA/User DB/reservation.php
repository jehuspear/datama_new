<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ensure that the request is a POST request

    // Database connection
    $hostName = "localhost";
    $dbUser = "root";
    $dbPassword = "";
    $dbName = "fmdbs_db";

    $conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

    // Check if the connection was successful
    if (!$conn) {
        // If connection failed, respond with an error
        $response = array("success" => false, "message" => "Database connection error.");
        echo json_encode($response);
        exit();
    }

    // Parse JSON data from the request body
    $requestData = json_decode(file_get_contents("php://input"), true);

    // Extract data from the parsed JSON
    $facilityId = $requestData["facilityId"];
    $reservationDate = $requestData["reservationDate"];
    $userId = $_SESSION["user_id"]; // Assuming you have the user ID stored in the session

    // SQL query to insert reservation data into tbl_reservation
    $sqlInsertReservation = "INSERT INTO TBL_RESERVATION (FACILITY_ID, USER_ID, RESERVATION_DATE, STATUS_ID)
                            VALUES (?, ?, ?, 1)"; // Assuming 1 is the status ID for 'Pending'

    $stmtInsertReservation = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmtInsertReservation, $sqlInsertReservation)) {
        mysqli_stmt_bind_param($stmtInsertReservation, "iss", $facilityId, $userId, $reservationDate);
        mysqli_stmt_execute($stmtInsertReservation);

        // Check if the insertion was successful
        if (mysqli_stmt_affected_rows($stmtInsertReservation) > 0) {
            // If successful, respond with success message
            $response = array("success" => true, "message" => "Reservation created successfully.");
        } else {
            // If not successful, respond with an error message
            $response = array("success" => false, "message" => "Failed to create reservation.");
        }

        mysqli_stmt_close($stmtInsertReservation);
    } else {
        // If preparation of the SQL statement failed, respond with an error
        $response = array("success" => false, "message" => "SQL statement preparation error.");
    }

    // Close the database connection
    mysqli_close($conn);

    // Encode the response as JSON and echo it
    echo json_encode($response);
} else {
    // If the request is not a POST request, respond with an error
    $response = array("success" => false, "message" => "Invalid request method.");
    echo json_encode($response);
}

?>
