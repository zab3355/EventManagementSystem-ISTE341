<?php
/*
    author: Zach Brown
    professor: Bryan French
    assignment: Project 1
    file: adduser.php
    function: Page add a user, Admin only.
*/

    // Check if user role is set as Admin, if not replace URL
    include('validate.php');
    if($_SESSION['userRole'] == "manager"){
        echo '<script>window.location.replace("registrations.php");</script>';
    }
    if($_SESSION['userRole'] == "attendee"){
        echo '<script>window.location.replace("registrations.php");</script>';
    }

    $mysql = new MySQLDatabase();

    // Set the user role and assign values dependent on role
    if(isset($_GET['updated'])){
        if($_POST['role'] == 'Admin'){
            $role = 1;
        }
        else if($_POST['role'] == 'Manager'){
            $role = 2;
        }
        else{
            $role = 3;
        }
    // Specify if user is added or not
        if($res = $mysql->insertUser($_POST['name'], $_POST['password'], $role)){
            echo "<script type='text/javascript'>alert('User added!')</script>";
        }
        else{
            echo "<script type='text/javascript'>alert('User was not added. Please try again.')</script>"; 
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
        <title>Admin Add User</title>
        <link rel="stylesheet" href="assets/css/style.css"/>
    
        <!-- Materialize.css-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/css/materialize.css" type="text/css" rel="stylesheet" >
    </head>
    <body>
        <!-- Header -->
        <nav class="#01579b light-blue darken-4 fixed" role="navigation">
            <div class="nav-wrapper container"><a id="logo-container" class="brand-logo">Add User</a>
                <!-- Navigation -->
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a  href="events.php">Events</a></li>
                    <li><a  href="registrations.php">Registrations</a></li>
                    <li><a  href="admin.php">Admin</a></li>
                    <li><a class="red darken-3 waves-effect waves-light btn" href="adduser.php?logout=true">Logout</a></li>
                </ul>
            </div>
        </nav>
        <div class="section container m9 s9">
        <!-- https://materializecss.com/-->
        <div class="z-depth-1 grey lighten-4 row" style="display: block; padding: 30px; text-align: center; border: 1px solid #EEE;">
            <!-- -->
            <?php
                echo '<form id="createAccountForm" action="adduser.php?updated=true" method="post">
                    Username:<input data-length="100" type="text" name="name" required="required" value=""><br/>
                    Password:<input data-length="100" type="password" name="password" required="required" value=""><br/>
                    Role:<br/>';
                echo '<p><label><input value="Admin" name="role" type="radio"><span>Admin</span></label></p>
                      <p><label><input value="Manager" name="role" type="radio"><span>Manager</span></label></p>
                      <p><label><input value="Attendee" name="role" type="radio" checked><span>Attendee</span></label></p>';
                echo '<br/><input class="btn #01579b light-blue darken-4" type="submit" value="Add User"></form>';  
            ?>
        </div>
        </div>
        <!--  Back Button  -->
     <a href="admin.php">
         <img alt="Back" src="assets/img/back.png" width="70" height="70">
      </a><br/>
        <!-- https://materializecss.com/ Scripts -->
        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="assets/js/materialize.js"></script>
        <script src="assets/js/init.js"></script>
    </body>
</html>
