<?php 


function create_calendar($month, $year) {

    $userId = $_GET["id"]; // get the user_id value in the url
    $facility_ID = $_GET["facility"]; // get the facility_id value in the url
    include 'database_user.php';
    
    // echo $facility_ID;
    // echo $userId;
    session_start(); // Start the session
    
    if (!isset($_SESSION["user_id"])) {
        // Redirect to the login page if the user is not logged in
        header("Location: login_user.php");
        exit();
    }
    
    // Fetch the user's information from the database 
    $user_id = $_SESSION["user_id"]; //WHO IS LOGGED IN


    // MYSQL DATABASE PROCESS
    $mysql = new mysqli("localhost", 'root' ,'', "fmdbs_db");
    $stmt = $mysql->prepare('SELECT * FROM tbl_reservation WHERE MONTH(RESERVATION_DATE) = ? AND YEAR(RESERVATION_DATE) = ? AND FACILITY_ID = ? ORDER BY RESERVATION_DATE ASC');
    $stmt->bind_param('sss',$month, $year, $facility_ID);
    $reserved_date = array();
    $status = array();
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            while($row = $result ->fetch_assoc()){
                $reserved_date[] = $row['RESERVATION_DATE'];
                $status[] = $row['STATUS_ID'];
            }
            // CLOSE DATABASE CONNECTION PROCESS
            $stmt->close();
        }
    }
    print_r($status);
    // print_r($facility);
    // print_r($reserved_date);
    // VARIABLES
    $daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

    // CURRENT/PRESENT
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $numberDays = date('t', $firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];

    $dateToday = date('Y-m-d');
    // PREVIOUS
    $prev_month = date('m', mktime(0, 0, 0, $month-1, 1, $year));
    $prev_year = date('Y', mktime(0, 0, 0, $month-1, 1, $year));
    // NEXT
    $next_month = date('m', mktime(0, 0, 0, $month+1, 1, $year));
    $next_year = date('Y', mktime(0, 0, 0, $month+1, 1, $year));

    // DISPLAY MONTH
    $calendar = "<center><h2>$monthName $year</h2>";
    // PREV
    $calendar.= "<a class='btn btn-primary btn-xs' href='?month=".$prev_month."&year=".$prev_year."&id=".$user_id."&facility=".$facility_ID."'>Previous Month</a>";

    // CURRENT
    $calendar.= "<a class='btn btn-primary btn-xs' style='margin: 0 12px 0 12px;' href='?month=".date('m')."&year=".date('Y')."&id=".$user_id."&facility=".$facility_ID."'>Current Month</a>";
    // $calendar.= "<button class='btn btn-info btn-m'>Current Month</button>";

    // NEXT
    $calendar.= "<a class='btn btn-primary btn-xs' href='?month=".$next_month."&year=".$next_year."&id=".$user_id."&facility=".$facility_ID."'>Next Month</a></center>";
    
    // DISPLAY DAYS: SUNDAY-SATURDAY (HEADER)
    $calendar.="<br><br><table class='table table-bordered'>";
    $calendar.="<tr>";
    foreach($daysOfWeek as $day){
        $calendar.="<th class='header'>$day</th>";
    }
    
    // DISPLAY DAY NUMBERS OF THE MONTH
    $calendar.="</tr><tr>";
    $currentDay = 1;
    if($dayOfWeek > 0){
        for($k = 0; $k < $dayOfWeek; $k++){
            $calendar.= "<td></td>";
        }
    }

    $month = str_pad($month, 2, "0", STR_PAD_LEFT);
    $ctr = 0;
    while($currentDay <= $numberDays){
        if($dayOfWeek == 7){
            $dayOfWeek = 0;
            $calendar.= "</tr><tr>";
        }
        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";
        $dayName = strtolower(date('I', strtotime($date)));

        $today = $date==date('Y-m-d') ? 'today':'';

        if($date < date('Y-m-d')){ //CHECK IF THE DAY IS YESTERDAYS
            $calendar.="<td class='$today'><h4>$currentDay</h4><button class='btn btn-danger btn-xs'>Not Available</button></td>";
        }
        // CHECK FROM DATABASE IF THAT DAY IN THE MONTH IS RESERVED
        elseif(in_array($date, $reserved_date) && $status[$ctr]==1){
            
            $calendar.="<td class='$today'><h4>$currentDay</h4><a class='btn btn-warning btn-xs'>Pending</a></td>";
            $ctr++;

            // I want the check the status of that DAY
            // if(in_array('1', $status_ID)){
            //     $calendar.="<td class='$today'><h4>$currentDay</h4><a class='btn btn-warning btn-xs'>Pending</a></td>";
            // }elseif(in_array('2', $status_ID)){
            //     $calendar.="<td class='$today'><h4>$currentDay</h4><a class='btn btn-danger btn-xs'>Already Reserved</a></td>";
            // }else{
            //     $calendar.="<td class='$today'><h4>$currentDay</h4><a class='btn btn-danger btn-xs'>Declined</a></td>";
            // }
            
        }elseif(in_array($date, $reserved_date) && $status[$ctr]==2){
            
            $calendar.="<td class='$today'><h4>$currentDay</h4><a class='btn btn-danger btn-xs'>Already Reserved</a></td>";
            $ctr++;

            // I want the check the status of that DAY
            // if(in_array('1', $status_ID)){
            //     $calendar.="<td class='$today'><h4>$currentDay</h4><a class='btn btn-warning btn-xs'>Pending</a></td>";
            // }elseif(in_array('2', $status_ID)){
            //     $calendar.="<td class='$today'><h4>$currentDay</h4><a class='btn btn-danger btn-xs'>Already Reserved</a></td>";
            // }else{
            //     $calendar.="<td class='$today'><h4>$currentDay</h4><a class='btn btn-danger btn-xs'>Declined</a></td>";
            // }
            
        }
        else{
            $calendar.="<td class='$today'><h4>$currentDay</h4><a href='handleReservation.php?date=$date&facility=$facility_ID&id=$user_id&status=1' class='btn btn-success btn-xs'>Reserve Now</a></td>";
        }

        
        $currentDay++;
        $dayOfWeek++;
}

if($dayOfWeek < 7){
    $remainingDays = 7-$dayOfWeek;
    for($i=0; $i < $remainingDays; $i++){
        $calendar.="<td></td>";
    }
}
    $calendar.="</tr><table>";


    return $calendar;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>

    <!-- BOOTSTRAP 3 -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <!-- <style>
        @media only screen and (max-width: 760px),
        (min-device-width: 802px) and (max-device-width: 1020px){
            /* Force table to not be like tables anymore */
            table,
            thead,
            tbody,
            th,
            td,
            tr{
                display: block;
            }
        }

        .empty{
            display: none;
        }

        /* HIDE */
        /* th{
            position: absolute;
            top: -9999px;
            left: -9999px;
        }
         */
        tr{
            border: 1px solid #ccc;
        }

        td{
            /* Behave like a row */
            border: none;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 50%;
        }

        /* Label the data
        td:nth-of-type(1):before{
            content:"Sunday";
        }

        td:nth-of-type(2):before{
            content:"Monday";
        }

        td:nth-of-type(3):before{
            content:"Tuesday";
        }

        td:nth-of-type(4):before{
            content:"Wednesday";
        }

        td:nth-of-type(5):before{
            content:"Thursday";
        }

        td:nth-of-type(6):before{
            content:"Friday";
        }

        td:nth-of-type(7):before{
            content:"Saturday";
        } */

        /* Smartphones */
        @media only screen and (min-device-width: 320px) and (max-device-width: 480px){
            body {
                padding: 0;
                margin: 0;
            }
        }

        /* iPads */
         @media only screen and (min-device-width: 802px) and (max-device-width: 1020px){
            body {
                width: 495px;
            }
        }

        @media (min-width: 641px) {
            table {
                table-layout: fixed;
            }
            td {
                width: 33%;
            }
        }

        .row{
            margin-top: 20px;
        }

        .today{
            background: lightyellow;
        }

    </style> -->

    <style>
        body{
            padding: 0px;
        }
         tr{
            border: 1px solid #ccc;
        }

        td{
            /* Behave like a row */
            border: none;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 50%;
        }
        .row{
            margin-top: 20px;
        }

        .today{
            background: lightyellow;
        }
    </style>


</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- PHP CALENDAR -->
            <?php 
                $dateComponents = getdate();
                if(isset($_GET['month']) && isset($_GET['year'])) {
                    $month = $_GET['month'];
                    $year = $_GET['year'];
                }else{
                    $month = $dateComponents['mon'];
                    $year = $dateComponents['year'];
                }

                echo create_calendar($month,$year);

            ?>
        </div>
    </div>
</div>

    
</body>
</html>