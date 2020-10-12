<?php
/*
    author: Zach Brown
    professor: Bryan French
    assignment: Project 1
    file: editsession.php
    function: Page to edit a session.
*/

    // Check if user role is set as Admin or Manager, if not replace URL
    include('validate.php');
    if($_SESSION['userRole'] == "attendee" || !isset($_GET['id'])){
        echo '<script>window.location.replace("registrations.php");</script>';
    }

    // If not an Admin role, check if the session is associated with the user logged in.
    if($_SESSION['userRole'] == "manager"){    
        $result = $mysql->getManagerEvents();
        $sess = $mysql->getSession($_GET['id']);
        $allowed = false;
        foreach($result as $response){
            if($response['idevent'] == $sess['event']){
                $allowed = true;
            }
        }
        if($allowed == false){
            echo '<script>window.location.replace("events.php");</script>';
        }
    }

    $mysql = new MySQLDatabase();

    // Delete a session function
    if(isset($_GET['deleteSession'])){
        $mysql->adminDelete("session", $_GET['id'], "idsession");
        echo '<script>window.location.replace("events.php");</script>';
    }

    // Update form with submitted fields
    if(isset($_GET['updated'])){
        $mysql->editSession($_POST['name'], $_POST['datestart'], $_POST['dateend'], $_POST['capacity'], $_POST['dropdown'], $_GET['id']);
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
        <title>Edit Session</title>
        <link rel="stylesheet" href="assets/css/style.css"/>
    
        <!-- Materialize.css-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/css/materialize.css" type="text/css" rel="stylesheet" >
    </head>
    <body>
        <!-- Header -->
        <nav class="#01579b light-blue darken-4 fixed" role="navigation">
            <div class="nav-wrapper container"><a id="logo-container" class="brand-logo">Edit Session</a>
                <!-- Navigation -->
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a  href="events.php">Events</a></li>
                    <li><a  href="registrations.php">Registrations</a></li>
                    <?php 
                        if($_SESSION['userRole'] == "admin"){
                            echo "<li><a href='admin.php'>Admin</a></li>";
                        }
                    ?>
                    <li><a class="red darken-3 waves-effect waves-light btn" href="editsession.php?logout=true">Logout</a></li>
                </ul>
            </div>
        </nav>

        <div class="section container m9 s9">
        <!-- https://materializecss.com/-->
        <div class="z-depth-1 grey lighten-4 row" style="display: block; padding: 30px; text-align: center; border: 1px solid #EEE;">
            <!-- Form to edit a session -->
            <?php
                $response = $mysql->getSession($_GET['id']);
                $result = $mysql->getManagerEvents();
                echo '<h5 class="center">Session ' . $response["idsession"] . '</h5><form id="createAccountForm" action="editsession.php?updated=true&id=' . $response["idsession"] . '" method="post">
                    Session Name:<input type="text" name="name" value="' . $response["name"] . '"><br/>
                    Start Date:<input type="text" name="datestart" value="' . $response["startdate"] . '"><br/>
                    End Date:<input type="text" name="dateend" value="' . $response["enddate"] . '"><br/>
                    Capacity:<input type="number" name="capacity" value="' . $response["numberallowed"] . '"><br/>
                    Event Association:';
                $event = '<select name="dropdown">';
                foreach($result as $res){
                    $event .= "<option value='" . $res['idevent'] . "'>" . $res['name'] . "</option>";
                }
                $event .= '</select>';
                echo $event . '<br/><input class="btn center #01579b light-blue darken-4" type="submit" value="Save Changes">&nbsp;';
                echo '<a class="btn center red darken-2" href="editsession.php?deleteSession=true&id=' . $response["idsession"] . '">Delete 
                    Session</a></form>';     
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
