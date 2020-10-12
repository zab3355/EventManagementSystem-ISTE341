<?php
/*
    author: Zach Brown
    professor: Bryan French
    assignment: Project 1
    file: adduser.php
    function: Page to edit a user for Admin user role only.
*/

    // Check if user role is set as Admin, if not replace URL
    include('validate.php');
    if($_SESSION['userRole'] == "manager"){
        echo '<script>window.location.replace("registrations.php");</script>';
    }
    if($_SESSION['userRole'] == "attendee"){
        echo '<script>window.location.replace("registrations.php");</script>';
    }

    // Check ID if correct for role
    if(!isset($_GET['id']) || ($_GET['id'] == 1)){
        echo '<script>window.location.replace("registrations.php");</script>';
    }

    $mysql = new MySQLDatabase();

    // Delete user function
    if(isset($_GET['deleteUser']) && !($_GET['id'] == 1)){
        $mysql->adminDelete("attendee", $_GET['id'], "idattendee");
        echo '<script>window.location.replace("admin.php");</script>';
    }

    // Update form with submitted fields
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
        $res = $mysql->adminEditUser($_POST['name'], $role, $_POST['id']);
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
        <title>Admin Edit User</title>
        <link rel="stylesheet" href="assets/css/style.css"/>
    
        <!-- Materialize.css-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/css/materialize.css" type="text/css" rel="stylesheet" >
    </head>
    <body>
        <!-- Header -->
        <nav class="#01579b light-blue darken-4 fixed" role="navigation">
            <div class="nav-wrapper container"><a id="logo-container" class="brand-logo">Edit User</a>
                <!-- Navigation -->
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="events.php">Events</a></li>
                    <li><a href="registrations.php">Registrations</a></li>
                    <li><a href="admin.php">Admin </a></li>
                    <li><a class="red darken-3 waves-effect waves-light btn" href="edituser.php?logout=true">Logout</a></li>
                </ul>
            </div>
        </nav>
        
        <div class="section container m9 s9">
        <!-- https://materializecss.com/-->
        <div class="z-depth-1 grey lighten-4 row" style="display: block; padding: 30px; text-align: center; border: 1px solid #EEE;">
            <!-- Edit user form -->
            <?php
                $response = $mysql->getAttendee($_GET['id']);
                echo '<form id="createAccountForm" action="edituser.php?updated=true&id=' . $response["idattendee"] . '" method="post">
                    ID:<input type="text" readonly name="id" value="' . $response["idattendee"] . '"><br/>
                    Name:<input type="text" name="name" value="' . $response["name"] . '"><br/>
                    Role:<br/>';
                echo '<p><label><input value="Admin" name="role" type="radio"';
                    if($response["role"] == 1){ 
                        echo ' checked';
                    }
                    echo '><span>Admin</span></label></p>
                      <p><label><input value="Manager" name="role" type="radio"';
                    if($response["role"] == 2){ 
                        echo ' checked';
                    }
                    echo '><span>Manager</span></label></p>
                      <p><label><input value="Attendee" name="role" type="radio"';
                    if($response["role"] == 3){ 
                        echo ' checked';
                    }
                    echo '><span>Attendee</span></label></p>';
                echo '<br/><input class="btn #01579b light-blue darken-4" type="submit" value="Save Changes">&nbsp;';
                echo '<a class="btn red darken-2" href="edituser.php?deleteUser=true&id=' . $response["idattendee"] . '">Delete 
                    User</a></form>';     
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
