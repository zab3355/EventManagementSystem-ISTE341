<?php
/*
    author: Zach Brown
    professor: Bryan French
    assignment: Project 1
    file: addvenue.php
    function: Page to add a venue
*/

    // Check if user role is set as Admin, if not replace URL
    include('validate.php');
    if($_SESSION['userRole'] == "attendee"){
        echo '<script>window.location.replace("registrations.php");</script>';
    }

    if($_SESSION['userRole'] == "manager"){
        echo '<script>window.location.replace("registrations.php");</script>';
    }

    $mysql = new MySQLDatabase();

    // Specify if venue is added or not
    if(isset($_GET['updated'])){
        if($res = $mysql->insertVenue($_POST['name'], $_POST['capacity']) == 1){
            echo "<script type='text/javascript'>alert('Venue added!')</script>";
        }
        else{
            echo "<script type='text/javascript'>alert('Venue was not added. Please try again.')</script>"; 
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
        <title>Add Venue</title>
        <link rel="stylesheet" href="assets/css/style.css"/>
    
        <!-- Materialize.css-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/css/materialize.css" type="text/css" rel="stylesheet" >
    </head>
    <body>
        <!-- Header -->
        <nav class="#01579b light-blue darken-4 fixed" role="navigation">
            <div class="nav-wrapper container"><a id="logo-container" class="brand-logo">Add Venue</a>
                <!-- Navigation -->
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="events.php">Events</a></li>
                    <li><a href="registrations.php">Registrations</a></li>
                    <li><a href='admin.php'>Admin</a></li>
                    <li><a class="red darken-3 waves-effect waves-light btn" href="addvenue.php?logout=true">Logout</a></li>
                </ul>
            </div>
        </nav>
        
        <div class="section container m9 s9">
        <!-- https://materializecss.com/-->
        <div class="z-depth-1 grey lighten-4 row" style="display: block; padding: 30px; text-align: center; border: 1px solid #EEE;">
          <!-- Adding venue form -->
            <?php
                $result = $mysql->getVenues();
                echo '<form id="createAccountForm" action="addvenue.php?updated=true" method="post">
                    Venue Name:<input data-length="50" type="text" name="name" required="required" value=""><br/>
                    Capacity:<input type="number" name="capacity" required="required" value="">';
                echo '<br/><input class="btn #01579b light-blue darken-4" type="submit" value="Add Venue"></form>';  
            ?>
        </div>
        </div>
    <!--  Back Button  -->
     <a href="admin.php">
         <img alt="Back" src="assets/img/back.png" width="70" height="70">
      </a><br/>
        
       <!-- https://materializecss.com/ Scripts-->
        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="assets/js/materialize.js"></script>
        <script src="assets/js/init.js"></script>
    </body>
</html>
