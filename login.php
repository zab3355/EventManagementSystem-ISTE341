<?php
/*
    author: Zach Brown
    professor: Bryan French
    assignment: Project 1
    file: login.php
    function: Login page
*/

    // Login and start session.
    include('MySQLDatabase.php');

    // Check if the user is logged in
    if(isset($_SESSION['userRole'])){
        echo '<script>window.location.replace("registrations.php");</script>';
    }

    if(session_status() == PHP_SESSION_ACTIVE){
        session_destroy();
    }

    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <!-- Source for HTML Template: https://bootsnipp.com/materialize/snippets/3MvKp            
        https://materializecss.com/-->
        <meta charset="utf-8">
        <title>Event Management System - Login</title>
        
        <link rel="stylesheet" href="assets/css/style.css">

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/css/materialize.css" type="text/css" rel="stylesheet">
    </head>
    <body>
                
        <!-- Nav Bar  -->
        <nav class="#01579b light-blue darken-4 fixed" role="navigation">
            <div class="nav-wrapper container"><a id="logo-container" class="brand-logo">Login</a>
            </div>
        </nav>
        <main>
        <div id="frame">
        <div class="section"></div>

            <h5 class="indigo-text">Welcome to my Event Management System! Please login to continue.</h5>
            <div class="section"></div>

            <div class="container">
        
            <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">

                <div class='row'>
                <div class='col s12'>
              </div>
            </div>

            <form method="post">
                <div>
                    <label for="username"><b>Username:</b></label>
                    <input id="username" type="text" placeholder="Enter Username" required="required" name="username">
                </div>

                <div>
                    <label for="password"><b>Password:</b></label>
                    <input id="password" type="password" placeholder="Enter Password" required="required" name="password">
                </div>

            <br/>
              <div class='row'>
                <button type='submit' name='btn_login' class='col s12 btn btn-large waves-effect indigo'><i class = "material-icons right">send</i>Login</button>
              </div>
          </form>
        </div>
      </div>
      <a href="register.php">Create an Account</a>
    </div>
    <div class="section"></div>
    <div class="section"></div>
  </main>

        <?php
            // Validate form 
            if(isset($_POST['username'])&& isset($_POST['password'])){
                $mysql = new MySQLDatabase();
                $res = $mysql->login($_POST['username'], $_POST['password']);
            }
        ?>
        
        <!-- https://materializecss.com/ Scripts -->
        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="assets/js/materialize.js"></script>
        <script src="assets/js/init.js"></script>
    </body>
</html>
