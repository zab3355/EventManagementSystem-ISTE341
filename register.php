<?php
/*
    author: Zach Brown
    professor: Bryan French
    assignment: Project 1
    file: register.php
    function: Page that creates a new user
*/

    include('MySQLDatabase.php');
    session_start();

    // Check if logged in
    if(isset($_SESSION['userRole'])){
        echo '<script>window.location.replace("registrations.php");</script>';
    }

    $mysql = new MySQLDatabase();

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
        if($res = $mysql->insertUser($_POST['name'], $_POST['password'], $role)){
            echo "<script type='text/javascript'>alert('User added!')</script>";
            header("Location: http://serenity.ist.rit.edu/~zab5957/341/project1/login.php");
        }
        else{
            echo "<script type='text/javascript'>alert('User was not added. Please try again.')</script>"; 
            header("Location: http://serenity.ist.rit.edu/~zab5957/341/project1/login.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Create an Account</title>
        <link rel="stylesheet" href="assets/css/style.css"/>
    
        <!-- Materialize.css-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/css/materialize.css" type="text/css" rel="stylesheet" >
    </head>
    <body>
        <!-- Header -->
        <nav class="#01579b light-blue darken-4 fixed" role="navigation">
            <div class="nav-wrapper container"><a id="logo-container" class="brand-logo">Create an Account</a>
            </div>
        </nav>

        <!--  Redirect to Login Page  -->
     <a href="login.php">
         <img alt="Back" src="assets/img/back.png"
         width="70" height="70">
      </a>
        
        <main>
        <div id="frame">
        <div class="section"></div>

            <h5 class="indigo-text">Sign up by filling in your desired credentials here.</h5>
            <div class="section"></div>

        <!-- Source for HTML Template: https://bootsnipp.com/materialize/snippets/3MvKp            
        https://materializecss.com/-->
        <div class="z-depth-1 grey lighten-4 row" style="display: inline-flex;
    padding: 20px 69px 0px 72px;
    border: 1px solid #EEE;">

        <div class="section container m9 s9">
            <?php
                echo '<form id="createAccountForm" action="adduser.php?updated=true" method="post">
                    <label for="username"><b>Username:</b></label><input id="username" data-length="100" type="text" name="name" required="required" value=""><br/>
                    <label for="password">
                    <b>Password:</b><input id="password" data-length="100" type="password" name="password" required="required" value=""><br/></label>
                    
                    Role:<br/>';
                
                    echo '<p><label><input value="Admin" name="role" type="radio">
                    <span>Admin</span></label>
                    </p>
                      <p><label><input value="Manager" name="role" type="radio"><span>Manager</span></label></p>
                      <p><label><input value="Attendee" name="role" type="radio" checked><span>Attendee</span></label></p>';
                 echo '<br/><input class="col s12 btn btn-large waves-effect indigo" type="submit" value="Register"> </form>';  
            ?>
        </div>
        </div>
        </div>
        </main>
        
        <!-- https://materializecss.com/ Scripts -->
        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="assets/js/materialize.js"></script>
        <script src="assets/js/init.js"></script>
    </body>
</html>
