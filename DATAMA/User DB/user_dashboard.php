<?php
include 'database_user.php';
session_start(); // Start the session

if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login_user.php");
    exit();
}

// Fetch the user's information from the database
$user_id = $_SESSION["user_id"];
$sqlUser = "SELECT FIRST_NAME FROM tbl_user WHERE USER_ID = ?";
$stmtUser = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmtUser, $sqlUser)) {
    mysqli_stmt_bind_param($stmtUser, "i", $user_id);
    mysqli_stmt_execute($stmtUser);
    mysqli_stmt_bind_result($stmtUser, $first_name);

    if (mysqli_stmt_fetch($stmtUser)) {
        // $first_name now contains the user's first name
    }

    // Close the statement
    mysqli_stmt_close($stmtUser);
}

// Fetch all facilities from tbl_facility including IMAGE_URL
$sqlFacility = "SELECT FACILITY_ID, FACILITY_NAME, IMAGE_URL FROM tbl_facility";
$resultFacility = mysqli_query($conn, $sqlFacility);


// Check for query error
if (!$resultFacility) {
    die("Error retrieving facilities: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="user_dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-cvvvhG+CmZlK5PDqpXl0Phjb5Q5aQg0YpxlF2VF13sF3mGLZjJu5qfNDeQ8p1q1x" crossorigin="anonymous"></script>


    <!-- BOOTSTRAP 3 -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <style>
        .calendar-container {
            display: none;
            margin-top: 20px;
        }

        .calendar {
            border-collapse: collapse;
            width: 100%;
        }

        .calendar th,
        .calendar td {
            border: 1px solid #dddddd;
            text-align: center;
            padding: 8px;
        }

        .calendar th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
<div class="header">
    <div class="header-content">
        <?php if (isset($first_name)) : ?>
            <h1>Welcome, <?php echo $first_name; ?>!</h1>
        <?php else : ?>
            <h1>Welcome to User Dashboard</h1>
        <?php endif; ?>
    </div>
    <a href="logout_user.php" class="btn btn-warning">Logout</a>
</div>


    

    <div class="container1">
    <h2>FACILITIES</h2>
    </div>

    <div class="container">
    <?php while ($rowFacility = mysqli_fetch_assoc($resultFacility)) : ?>
        <?php $facility_name = $rowFacility["FACILITY_NAME"]; echo $facility_name ?>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4"> <!-- Adjust the column size based on your layout -->
                        <img src="http://localhost:8081/DATAMA/datama_new/DATAMA/Images/<?php echo $rowFacility['IMAGE_URL']; ?>" class="card-img-top" alt="Facility Image" style="width: 100%; height: auto; border-radius: 10px; border: 2px solid black;">
                    </div>
                    <div class="col-md-8"> <!-- Adjust the column size based on your layout -->
                        <h2 class="card-title"><?php echo $rowFacility['FACILITY_NAME']; ?></h2>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        <!-- BUTTON TO SHOW CALENDAR -->
                        <!-- <button class="btn btn-primary reserve-button" data-facility-id="<?php echo $rowFacility['FACILITY_ID']; ?>">Reserve</button> -->
                        <a class="btn btn-primary reserve-button" target="_blank" href="calendar.php?id=<?php echo '$user_id'?>&<?php echo "facility=$facility_name"?>">Reserve</a>
            
                        <!-- <button class="btn btn-primary" id="toggleCalendarBtn">Toggle Calendar</button> -->

                        

                    </div>
                </div>
                <iframe src="calendar.php" width="100%" height="700px"></iframe>
         

                                    
                        
                

            </div>
        </div>
    <?php endwhile; ?>
</div>




</div>

</body>

</html>
