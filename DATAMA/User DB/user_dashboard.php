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
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4"> <!-- Adjust the column size based on your layout -->
                        <img src="http://localhost/<?php echo $rowFacility['IMAGE_URL']; ?>" class="card-img-top" alt="Facility Image" style="width: 100%; height: auto; border-radius: 10px; border: 2px solid black;">
                    </div>
                    <div class="col-md-8"> <!-- Adjust the column size based on your layout -->
                        <h2 class="card-title"><?php echo $rowFacility['FACILITY_NAME']; ?></h2>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        <button class="btn btn-primary reserve-button" data-facility-id="<?php echo $rowFacility['FACILITY_ID']; ?>">Reserve</button>
                    </div>
                </div>
                <div class="calendar-container" id="calendar-<?php echo $rowFacility['FACILITY_ID']; ?>">
                    <table class="calendar table">
                        <thead>
                            <tr>
                                <th>Sun</th>
                                <th>Mon</th>
                                <th>Tue</th>
                                <th>Wed</th>
                                <th>Thu</th>
                                <th>Fri</th>
                                <th>Sat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Calendar days will be dynamically generated using JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>




</div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reserveButtons = document.querySelectorAll('.reserve-button');

            reserveButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const facilityId = this.getAttribute('data-facility-id');
                    const calendarContainer = document.getElementById(`calendar-${facilityId}`);

                    // Toggle the visibility of the calendar
                    calendarContainer.style.display = (calendarContainer.style.display === 'none' || calendarContainer.style.display === '') ? 'block' : 'none';

                    if (calendarContainer.style.display === 'block') {
                        // Dynamically generate the calendar days for March 2024
                        const calendarBody = calendarContainer.querySelector('tbody');
                        calendarBody.innerHTML = '';

                        const daysInMarch = 31;
                        const firstDay = new Date(2024, 2, 1); // March is 2 in JavaScript Date object
                        const startingDay = firstDay.getDay();

                        for (let i = 0; i < Math.ceil((daysInMarch + startingDay) / 7); i++) {
                            const row = document.createElement('tr');

                            for (let j = 0; j < 7; j++) {
                                const day = i * 7 + j - startingDay + 1;
                                const cell = document.createElement('td');

                                if (day > 0 && day <= daysInMarch) {
                                    cell.textContent = day;

                                    // Add click event for each date
                                    cell.addEventListener('click', function () {
                                        const selectedDate = new Date(2024, 2, day); // March is 2 in JavaScript Date object
                                        const formattedDate = selectedDate.toISOString().split('T')[0];

                                        // Ask for confirmation
                                        const isConfirmed = confirm(`Do you want to reserve on ${formattedDate}?`);

                                        if (isConfirmed) {
                                            // Create reservation
                                            const reservationData = {
                                                facilityId: facilityId,
                                                reservationDate: formattedDate
                                            };

                                            fetch('create_reservation.php', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json'
                                                },
                                                body: JSON.stringify(reservationData)
                                            })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.success) {
                                                        alert('Reservation created successfully!');
                                                    } else {
                                                        alert('Failed to create reservation.');
                                                    }
                                                })
                                                .catch(error => {
                                                    console.error('Error creating reservation:', error);
                                                });
                                        }
                                    });
                                }

                                row.appendChild(cell);
                            }

                            calendarBody.appendChild(row);
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>
