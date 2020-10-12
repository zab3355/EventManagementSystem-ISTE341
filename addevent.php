<?php
/*
    author: Zach Brown
    professor: Bryan French
    assignment: Project 1
    file: addevent.php
    function: Page to add an event.
*/

    // Check if user role is set as Admin or Manager, if not replace URL
    include('validate.php');
    if($_SESSION['userRole'] == "attendee"){
        echo '<script>window.location.replace("registrations.php");</script>';
    }

    $mysql = new MySQLDatabase();

    // Update event based upon form info, then specify if event is added or not
    if(isset($_GET['updated'])){
        if($res = $mysql->insertEvent($_POST['name'], $_POST['datestart'], $_POST['dateend'], $_POST['capacity'], $_POST['dropdown'], $_SESSION['username']) == 1){
            echo "<script type='text/javascript'>alert('Event added!')</script>";
        }
        else{
            echo "<script type='text/javascript'>alert('Event was not added. Please try again.')</script>"; 
        }
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
        <meta charset="utf-8" />
        <title>Add Event</title>
        <link rel="stylesheet" href="assets/css/style.css"/>
    
        <!-- Materialize.css-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/css/materialize.css" type="text/css" rel="stylesheet" >
    </head>
    <body>
        <!-- Header -->
        <nav class="#01579b light-blue darken-4 fixed" role="navigation">
            <div class="nav-wrapper container"><a id="logo-container" class="brand-logo">Add Event</a>
                <!-- Navigation -->
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="events.php">Events</a></li>
                    <li><a href="registrations.php">Registrations</a></li>
                    <?php 
                        if($_SESSION['userRole'] == "admin"){
                            echo "<li><a href='admin.php'>Admin </a></li>";
                        }
                    ?>
                    <li><a class="red darken-3 waves-effect waves-light btn" href="addevent.php?logout=true">Logout</a></li>
                </ul>
            </div>
        </nav>
        
        <div class="section container m9 s9">
        <!-- https://materializecss.com/-->
        <div class="z-depth-1 grey lighten-4 row" style="display: block; padding: 30px; text-align: center; border: 1px solid #EEE;">
            <!-- Adding event form -->
            <?php
                $result = $mysql->getVenues();
                echo '<form id="createAccountForm" action="addevent.php?updated=true" method="post">
                    Event Name:<input data-length="50" type="text" name="name" required="required" value=""><br/>
                    Start Date:<input type="datetime-local" name="datestart" required="required" value=""><br/>
                    End Date:<input type="datetime-local" name="dateend" required="required" value=""><br/>
                    Capcity:<input type="number" name="capacity" required="required" value=""><br/>
                    Venue:<div class=input-field col s12>';
                $venue = '<select name="dropdown">';
                foreach($result as $res){
                    $venue .= "<option value='" . $res['idvenue'] . "'>" . $res['name'] . "</option>";
                }
                $venue .= '</select>';
                echo $venue . '<br/><input class="btn #01579b light-blue darken-4" type="submit" value="Add Event"></form>';  
            ?>
        </div>
        </div>
        <!-- Back button -->
         <a href="events.php">
            <img alt="Back" src="assets/img/back.png" width="70" height="70">
        </a>
        
        <!-- https://materializecss.com/ Scripts -->
        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="assets/js/materialize.js"></script>
        <script src="assets/js/init.js"></script>
    </body>
</html>
