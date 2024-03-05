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
    <link rel="stylesheet" href="user_dashboard.css">
    <!-- BOOTSTRAP 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-cvvvhG+CmZlK5PDqpXl0Phjb5Q5aQg0YpxlF2VF13sF3mGLZjJu5qfNDeQ8p1q1x" crossorigin="anonymous"></script>




    <!-- BOOTSTRAP FOR MODAL -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
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


    

    <div class="container" style="margin-top: 30px;">
    <h2>FACILITIES</h2>
    </div>

    <div class="container">
    <?php while ($rowFacility = mysqli_fetch_assoc($resultFacility)) : ?>
        <?php $facility_ID = $rowFacility["FACILITY_ID"]; //GETTING THE FACILITY ID ?>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4"> <!-- Adjust the column size based on your layout -->
                        <img src="http://localhost:8081/DATAMA/datama_new/DATAMA/Images/<?php echo $rowFacility['IMAGE_URL']; ?>" class="card-img-top" alt="Facility Image" style="width: 100%; height: auto; border-radius: 10px; border: 2px solid black;">
                    </div>
                    <div class="col-md-8"> <!-- Adjust the column size based on your layout -->
                        <h2 class="card-title"><?php echo $rowFacility['FACILITY_NAME']; ?></h2>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                       
                        <a href="calendar.php?month=03&year=2024&id=<?php echo $user_id;?>&<?php echo "facility=$facility_ID"?>"> GO TO CALENDAR</a>
                        <!-- BUTTON TO SHOW RESERVATION CALENDAR -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal<?php echo $facility_ID;?>">
                        Reserve Now
                        </button>

                    </div>
                </div>

                <!-- RESERVATION CALENDAR VIEW -->
                
                <!-- The Modal -->
                <div class="modal fade" id="myModal<?php echo $facility_ID;?>">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                        
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h1 class="modal-title"><?php echo $rowFacility['FACILITY_NAME']; ?></h1>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        
                        <!-- Modal body -->
                        <div class="modal-body">
                            <iframe id="calendarIframe<?php echo $facility_ID;?>" src="calendar.php?month=03&year=2024&id=<?php echo $user_id;?>&<?php echo "facility=$facility_ID"?>" width="100%" height="690px"></iframe>
                        </div>
                        
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                        
                        </div>
                    </div>
                </div>
         

                                    
                        
                

            </div>
        </div>
    <?php endwhile; ?>
</div>




</div>

<!-- JAVASCRIPT FOR THE MODAL -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
  $(document).ready(function(){
    $('.btn-primary').click(function(){
      var targetModal = $(this).attr('data-target');
      var iframeSrc = $(this).attr('data-iframe-src');
      $(targetModal).find('.calendar-iframe').attr('src', iframeSrc);
    });

    $('.modal').on('hidden.bs.modal', function () {
      $(this).find('.calendar-iframe').attr('src', '');
    });
  });
</script>

</body>

</html>
