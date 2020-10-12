<?php
/*
    author: Zach Brown
    professor: Bryan French
    assignment: Project 1
    file: editvenue.php
    function: Page to edit a venue
*/

    // Check if user role is set as Admin, if not replace URL
    include('validate.php');
    if($_SESSION['userRole'] == "manager"){
        echo '<script>window.location.replace("registrations.php");</script>';
    }
    if($_SESSION['userRole'] == "attendee"){
        echo '<script>window.location.replace("registrations.php");</script>';
    }
    // Check ID if ID exists
    if(!isset($_GET['id'])){
        echo '<script>window.location.replace("registrations.php");</script>';
    }

    $mysql = new MySQLDatabase();

    // Delete a venue function
    if(isset($_GET['deleteVenue']) && !($_GET['id'] == 1)){
        $mysql->adminDelete("venue", $_GET['id'], "idvenue");
        $mysql->adminDelete("event", $_GET['id'], "venue");
        echo '<script>window.location.replace("admin.php");</script>';
    }

    // Update form with submitted fields
    if(isset($_GET['updated'])){
        $res = $mysql->editVenue($_POST['name'], $_POST['capacity'], $_POST['id']);
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
        <title>Edit Venue</title>
        <link rel="stylesheet" href="assets/css/style.css"/>
    
        <!-- Materialize.css-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/css/materialize.css" type="text/css" rel="stylesheet" >
    </head>
    <body>
        <!-- Header -->
        <nav class="#01579b light-blue darken-4 fixed" role="navigation">
            <div class="nav-wrapper container"><a id="logo-container" class="brand-logo">Edit Venue</a>
                <!-- Navigation -->
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a  href="events.php">Events</a></li>
                    <li><a  href="registrations.php">Registrations</a></li>
                    <li><a  href="admin.php">Admin </a></li>
                    <li><a class="red darken-3 waves-effect waves-light btn" href="editvenue.php?logout=true">Logout</a></li>
                </ul>
            </div>
        </nav>

        <div class="section container m9 s9">
        <!-- https://materializecss.com/-->
        <div class="z-depth-1 grey lighten-4 row" style="display: block; padding: 30px; text-align: center; border: 1px solid #EEE;">
            <!-- Edit venue form -->
            <?php
                $response = $mysql->getVenue($_GET['id']);
                echo '<form id="createAccountForm" action="editvenue.php?updated=true&id=' . $response["idvenue"] . '" method="post">
                    ID:<input type="text" readonly name="id" value="' . $response["idvenue"] . '"><br/>
                    Name:<input type="text" name="name" value="' . $response["name"] . '"><br/>
                    Capacity:<input type="number" name="capacity" value="' . $response["capacity"] . '">';
                echo '<br/><input class="btn #01579b light-blue darken-4" type="submit" value="Save Changes">&nbsp;';
                echo '<a class="btn red darken-2" href="editvenue.php?deleteVenue=true&id=' . $response["idvenue"] . '">Delete 
                    Venue</a></form>';     
            ?>
        </div>
        </div>
        <!-- Back Button -->
        <a href="admin.php">
            <img alt="Back" src="assets/img/back.png" width="70" height="70">
        </a><br/>
        
        <!-- https://materializecss.com/ Scripts -->
        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="assets/js/materialize.js"></script>
        <script src="assets/js/init.js"></script>
    </body>
</html>
