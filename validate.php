<?php
/*
    author: Zach Brown
    professor: Bryan French
    assignment: Project 1
    file: validate.php
    function: Include SQL Database and functions, and replace location dependent on user role
*/

    include('MySQLDatabase.php');
    session_start();

    if(!isset($_SESSION['userRole'])){
        echo '<script>window.location.replace("login.php");</script>';
    }
?>


