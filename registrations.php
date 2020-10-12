<?php
/*
    author: Zach Brown
    professor: Bryan French
    assignment: Project 1
    file: registrations.php
    function: Page where user can view and edit registrations
*/

    include('validate.php');
    $mysql = new MySQLDatabase();
    
    // Delete an attending event function
    if(isset($_GET['deleteEvent'])){
        $mysql->delete("attendee_event", $_GET['id'], "event");
    }
    // Delete an attending session function
    if(isset($_GET['deleteSession'])){
        $mysql->delete("attendee_session", $_GET['id'], "session");
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
        <title>Registrations Panel</title>
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="shortcut icon" href="favicon.png" type="image/x-icon"/>
        
        <!-- Materialize.css-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/css/materialize.css" type="text/css" rel="stylesheet" >
    </head>
    <body>
        <!-- Header -->
        <nav class="#01579b light-blue darken-4 fixed" role="navigation">
            <div class="nav-wrapper container"><a id="logo-container" class="brand-logo">Registrations Panel</a>
                <!-- Navigation -->
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a  href="events.php">Events</a></li>
                    <li><a href="registrations.php">Registrations</a></li>
                    <?php 
                        if($_SESSION['userRole'] == "admin"){
                            echo "<li><a href='admin.php'>Admin</a></li>";
                        }
                    ?>
                    <li><a class="red darken-3 waves-light btn" href="registrations.php?logout=true">Logout</a></li>
                </ul>
            </div>
        </nav>

        <div class="section container s11 m11">
        <!-- https://materializecss.com/-->
        <div class="z-depth-1 grey lighten-4 row" style="display: block; padding: 20px 69px 0px 72px; border: 1px solid #EEE;">
            <!-- Show events attending -->
            <?php
                // Get registered events from the database
                $result = $mysql->getRegisteredEvents($_SESSION["username"]);
                echo "<h4 class='center'>Events Attending</h4>";
                foreach($result as $v){
                    echo "<p>";
                    foreach($v as $v2){
                        echo $v2 . " - ";
                    }
                    echo '</p><br/><a class="btn red darken-2" href="registrations.php?deleteEvent=true&id=' . $v["idevent"] . '">Stop 
                        Attending</a><br/>';
                } 
                // Get registered sessions from database depending on user
                $result2 = $mysql->getRegisteredSessions($_SESSION["username"]);
                echo "<h4 class='center'>Sessions Attending</h4>";
                foreach($result2 as $v2){
                    echo "<p>";
                    foreach($v2 as $v3){
                        echo $v3 . " - ";
                    }
                    echo '</p><br/><a class="btn red darken-2" href="registrations.php?deleteSession=true&id=' . $v2["idsession"] . '">Stop 
                        Attending</a><br/>';
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
