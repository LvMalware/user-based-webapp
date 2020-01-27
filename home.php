<?php
    
    require "utils.php";

    session_start();
    remove_duplicate_cookies();

    if (!isset($_SESSION['loggedin']) || ($_SESSION['loggedin'] !== true))
    {
        header("location: login.php");
        exit;
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title> User home </title>
        <link rel="stylesheet" href="css/default.css">
    </head>
    <body>
        <div class="top_bar">
            <p>SITE TITLE</p>
        </div>
        <?php
            
            echo "<p>Welcome, ".$_SESSION['username']."<p><br>";  
        ?>
        <div class="bottom_menu">
            <a href="logout.php"> Log out </a>
            <br>
            <a href="change_password.php"> Change Password </a>
        </div>
    </body>
</html>