<?php

    require "utils.php";

    session_start();
    remove_duplicate_cookies();

    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
    {
        header("location: home.php");
        exit;
    }

    if (isset($_POST['bt_login']))
    {
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        if (!$username || (strlen($username) < 5))
        {
            header("Location: login.html?error=0");
        }
        if (strlen($_POST['password']) < 10)
        {
            header("Location: login.html?error=1");
        }
        $password = hash('sha512', $_POST['password']);
        
        $sqlite = new SQLiteDB();
        $sqlite->busyTimeout(5000);
        $sqlite->exec("PRAGMA journal_mode=WAL;");
        
        $user_info = $sqlite->querySingle(
            "SELECT * FROM users WHERE `username` = '$username'
                                    AND `password` = '$password';",
            true
        );
        
        if ($user_info)
        {
            $id = $user_info['id'];
            $sqlite->exec(
                "UPDATE users
                 SET `last_login` = datetime('now')
                 WHERE `id` = '$id';");
            
            session_start();
            remove_duplicate_cookies();
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['id']       = $id;

            $sqlite->close();
            header("location: home.php");
        }
        else
        {
            $sqlite->close();
            header("Location: login.html?error=2");
        }
    }
    else
    {
        header("Location: login.html");
    }
?>