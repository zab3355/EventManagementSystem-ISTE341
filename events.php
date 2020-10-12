<?php
/*
    author: Zach Brown
    professor: Bryan French
    assignment: Project 1
    file: events.php
    function: Show database information for events and current sessions
*/

    include('validate.php');
    $mysql = new MySQLDatabase();

    // Delete attending event function
    if(isset($_GET['deleteAttendingEvent'])){
        $mysql->delete("attendee_event", $_GET['id'], "event");
    }
    // Delete attending session function
    if(isset($_GET['deleteAttendingSession'])){
        $mysql->delete("attendee_session", $_GET['id'], "session");
    }
    // Delete event function
    if(isset($_GET['deleteEvent'])){
        $mysql->adminDelete("event", $_GET['id'], "idevent");
        $mysql->adminDelete("session", $_GET['id'], "event");
    }
    // Delete session function
    if(isset($_GET['deleteSession'])){
        $mysql->adminDelete("session", $_GET['id'], "idsession");
    }

    // Attending event function
    if(isset($_GET['attendEvent'])){
        $mysql->attendEvent($_GET['id'], $_SESSION['username']);
    }
    // Attending session function
    if(isset($_GET['attendSession'])){
        $mysql->attendSession($_GET['id'], $_SESSION['username']);
    }

    // If logout button is selected, log out
	if(isset($_GET['logout'])){
        logout();
    }
     // Destroy the session and redirect to login
	function logout(){
		session_destroy();
		header("Location: http://serenity.ist.rit.edu/~zab5957/341/project1/login.php");
		exit();
	}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Events Panel</title>
        <link rel="stylesheet" href="assets/css/style.css">

        <!-- Materialize.css-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/css/materialize.css" type="text/css" rel="stylesheet" >
    </head>
    <body>
        <!-- Header -->
        <nav class="#01579b light-blue darken-4 fixed" role="navigation">
            <div class="nav-wrapper container"><a id="logo-container" class="brand-logo">Events Panel</a>
                <!-- Navigation -->
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="events.php">Events</a></li>
                    <li> <a href="registrations.php">Registrations</a></li>
                    <?php 
                        if($_SESSION['userRole'] == "admin"){
                            echo "<li><a href='admin.php'>Admin </a></li>";
                        }
                    ?>
                    <li><a class="red darken-3 waves-effect waves-light btn" href="events.php?logout=true">Logout</a></li>
                </ul>
            </div>
        </nav>

        <div class="section container s11 m11">
        <!-- https://materializecss.com/-->
        <div class="z-depth-1 grey lighten-4 row" style="display: block; padding: 20px 69px 0px 72px; border: 1px solid #EEE;">
            
            <!-- Display events and sessions -->
            <?php
                // Add event and session buttons for user roles Admin and Manager
                if(($_SESSION['userRole'] == "manager") || ($_SESSION['userRole'] == "admin")){
                    echo '<div class="center">
                            <h2 class="indigo-text">Events</h2>
                            <a class="btn #01579b light-blue darken-4 " href="addevent.php">Add Event</a>&nbsp;
                            <a class="btn #01579b light-blue darken-4 " href="addsession.php">Add Session</a><br/>
                        </div><br/>';
                    
                    $result = $mysql->getManagerEvents();
                    
                    // Print all events and sessions tied to the manager or admin
                    foreach($result as $response){
                        echo '<h5 class="center">Event ' . $response["idevent"] . '</h5><form action="" method="post">
                            Name:<input readonly type="text" name="nameEvent" value="' . $response["name"] . '"><br/>
                            Start Date:<input readonly type="text" name="dateStart" value="' . $response["datestart"] . '"><br/>
                            End Date:<input readonly type="text" name="dateEnd" value="' . $response["dateend"] . '"><br/>
                            Number Allowed:<input readonly type="text" name="eventNumAllowed" value="' . $response["numberallowed"] . '"><br/>
                            Venue:<input readonly type="text" name="eventVenue" value="' . ($mysql->getVenue($response["venue"]))["name"] . '"><br/>';
                        echo '<br/><a class="btn #01579b light-blue darken-4" href="editevent.php?id=' . $response["idevent"] . '">Edit 
                            Event</a>&nbsp;';
                        echo '<a class="btn red darken-2" href="events.php?deleteEvent=true&id=' . $response["idevent"] . '">Delete 
                            Event</a>';
                        echo '</form><br/>';
                        $result2 = $mysql->getSessionByEvent($response["idevent"]);
                        
                        if(!($result2 == null)){
                            foreach($result2 as $response2){
                                echo '<h5 class="center">Session for Listed Event</h5><form action="" method="post">
                                    Name:<input readonly type="text" name="nameSess" value="' . $response2["name"] . '"><br/>
                                    Start date:<input readonly type="text" name="startDate" value="' . $response2["startdate"] . '"><br/>
                                    End date:<input readonly type="text" name="endDate" value="' . $response2["enddate"] . '"><br/>
                                    Number Allowed:<input readonly type="text" name="sessNumAllowed" value="' . $response2["numberallowed"] . '"><br/>';
                                echo '<br/><a class="btn #01579b light-blue darken-4" href="editsession.php?id=' . $response2["idsession"] . '">Edit 
                                    Session</a>&nbsp;';
                                echo '<a class="btn red darken-2" href="events.php?deleteSession=true&id=' . $response2["idsession"] . 
                                    '">Delete Session</a>';
                                echo '</form><br/>';
                            }
                        }
                        echo '<hr/><br/>';
                    }
                }
            
                // Get events from database
                $result = $mysql->getEvents();
                echo "<h4 class='center'>All Events</h4>";
            
                foreach($result as $response){
                    echo '<form action="" method="post">
                        Name:<input readonly type="text" name="nameEvent" value="' . $response["name"] . '"><br/>
                        Date Start:<input readonly type="text" name="dateStart" value="' . $response["datestart"] . '"><br/>
                        Date End:<input readonly type="text" name="dateEnd" value="' . $response["dateend"] . '"><br/>
                        Number Allowed:<input readonly type="text" name="eventNumAllowed" value="' . $response["numberallowed"] . '"><br/>
                        Venue:<input readonly type="text" name="eventVenue" value="' . ($mysql->getVenue($response["venue"]))["name"] . '"><br/>';
                    
                    // Check if user is already in a specific event
                    $attending = false;
                    
                    foreach($mysql->getRegisteredEvents($_SESSION['username']) as $res) 
                        if($res['idevent'] == $response['idevent']){
                            echo '</p><br/><a class="btn red darken-2" href="events.php?deleteAttendingEvent=true&id=' . $response["idevent"] . '">Stop Attending</a><br/>';
                            $attending = true;
                    }
                    
                    if($attending == false){
                        echo '<br/><a class="btn #01579b light-blue darken-4" href="events.php?sattendEvent=true&id=' . $response["idevent"] . 
                            '">Attend</a>&nbsp;';   
                    }
                    
                    echo '</form><br/><hr/><br/>';
                }
            
                // Get sessions from database
                $result = $mysql->getSessions();
                echo "<h4 class='center'>All Sessions</h4>";
                foreach($result as $response){
                    echo '<form action="" method="post">
                        Name:<input readonly type="text" name="nameSess" value="' . $response["name"] . '"><br/>
                        Start date:<input readonly type="text" name="startDate" value="' . $response["startdate"] . '"><br/>
                        End date:<input readonly type="text" name="endDate" value="' . $response["enddate"] . '"><br/>
                        Number Allowed:<input readonly type="text" name="sessNumAllowed" value="' . $response["numberallowed"] . '"><br/>
                        Event:<input readonly type="text" name="sessEvent" value="' . ($mysql->getEvent($response["event"]))["name"] . '"><br/>';
                    
                    // Check if user is already in a specific session
                    $attending2 = false;
                    
                    foreach($mysql->getRegisteredSessions($_SESSION['username']) as $res) 
                        if($res['idsession'] == $response['idsession']){
                            echo '</p><br/><a class="btn red darken-2" href="events.php?deleteAttendingSession=true&id=' . $response["idsession"] . '">Stop Attending</a><br/>';
                            $attending2 = true;
                    }
                    
                    if($attending2 == false){
                        echo '<br/><a class="btn #01579b light-blue darken-4" href="events.php?attendSession=true&id=' . $response["idsession"] . 
                            '">Attend</a>&nbsp;';   
                    }
                    
                    echo '</form><br/><hr/><br/>';
                }
            ?>
        </div>
        </div>
        <!-- https://materializecss.com/ Scripts -->
        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="assets/js/materialize.js"></script>
        <script src="assets/js/init.js"></script>
    </body>
</html>
