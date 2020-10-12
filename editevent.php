<?php
/*
    author: Zach Brown
    professor: Bryan French
    assignment: Project 1
    file: editevent.php
    function: Page to edit an event
*/

    // Check if user role is set as Admin or Manager, if not replace URL
    include('validate.php');
    if($_SESSION['userRole'] == "attendee" || !isset($_GET['id'])){
        echo '<script>window.location.replace("registrations.php");</script>';
    }

    // If not an Admin role, check if the event is associated with the user logged in.
    if($_SESSION['userRole'] == "manager"){    
        $result = $mysql->getManagerEvents();
        $allowed = false;
        foreach($result as $response){
            if($response['idevent'] == $_GET['id']){
                $allowed = true;
            }
        }
        if($allowed == false){
            echo '<script>window.location.replace("events.php");</script>';
        }
    }

    $mysql = new MySQLDatabase();

    // Delete an event function
    if(isset($_GET['deleteEvent'])){
        $mysql->adminDelete("event", $_GET['id'], "idevent");
        $mysql->adminDelete("session", $_GET['id'], "event");
        echo '<script>window.location.replace("events.php");</script>';
    }

    // Update form with submitted fields
    if(isset($_GET['updated'])){
        $mysql->editEvent($_POST['name'], $_POST['datestart'], $_POST['dateend'], $_POST['capacity'], $_POST['dropdown'], $_GET['id']);
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
        <title>Edit Event</title>
        <link rel="stylesheet" href="assets/css/style.css"/>
    
        <!-- Materialize.css-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/css/materialize.css" type="text/css" rel="stylesheet" >
    </head>
    <body>
        <!-- Header -->
        <nav class="#01579b light-blue darken-4 fixed" role="navigation">
            <div class="nav-wrapper container"><a id="logo-container" class="brand-logo">Edit Event</a>
                <!-- Navigation -->
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a  href="events.php">Events</a></li>
                    <li><a  href="registrations.php">Registrations</a></li>
                    <?php 
                        if($_SESSION['userRole'] == "admin"){
                            echo "<li><a href='admin.php'>Admin </a></li>";
                        }
                    ?>
                    <li><a class="red darken-3 waves-effect waves-light btn" href="editevent.php?logout=true">Logout</a></li>
                </ul>
            </div>
        </nav>

        <div class="section container m9 s9">
        <!-- https://materializecss.com/-->
        <div class="z-depth-1 grey lighten-4 row" style="display: block; padding: 30px; text-align: center; border: 1px solid #EEE;">
            <!-- Edit event form  -->
            <?php
                $response = $mysql->getEvent($_GET['id']);
                $result = $mysql->getVenues();
                echo '<h5 class="center">Event ' . $response["idevent"] . '</h5><form id="createAccountForm" action="editevent.php?updated=true&id=' . $response["idevent"] . '" method="post">
                    Event Name:<input type="text" name="name" value="' . $response["name"] . '"><br/>
                    Start Date:<input type="text" name="datestart" value="' . $response["datestart"] . '"><br/>
                    End Date:<input type="text" name="dateend" value="' . $response["dateend"] . '"><br/>
                    Capcity:<input type="number" name="capacity" value="' . $response["numberallowed"] . '"><br/>
                    Venue:';
                $venue = '<select name="dropdown">';
                foreach($result as $res){
                    $venue .= "<option value='" . $res['idvenue'] . "'>" . $res['name'] . "</option>";
                }
                $venue .= '</select>';
                echo $venue . '<br/><input class="btn center #01579b light-blue darken-4" type="submit" value="Save Changes">&nbsp;';
                echo '<a class="btn center red darken-2" href="editevent.php?deleteEvent=true&id=' . $response["idevent"] . '">Delete 
                    Event</a></form>';     
            ?>
        </div>
        </div>
        
        <!-- Back Button -->
        <a href="events.php">
            <img alt="Back" src="assets/img/back.png" width="70" height="70">
        </a>
        
        <!-- https://materializecss.com/ Scripts -->
        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="assets/js/materialize.js"></script>
        <script src="assets/js/init.js"></script>
    </body>
</html>
