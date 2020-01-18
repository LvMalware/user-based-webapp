<?php

    class SQLiteDB extends SQLite3
    {
        function __construct()
        {
            $this->open("db/mydatabase.db");
        }
    }

    if (isset($_POST['bt_submit']))
    {
        $email_addr = filter_var(
            $_POST['email_address'], FILTER_SANITIZE_EMAIL
        );

        if (!$email_addr)
        {
            header("Location: registration.html?error=0");
        }

        $first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
        $last_name  = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
        $username   = filter_var($_POST['username'], FILTER_SANITIZE_STRING);

        if (strlen($username) < 5)
        {
            header("Location: registration.html?error=5");
        }

        if (strlen($_POST['password']) < 10)
        {
            header("Location: registration.html?error=4");
        }

        $password   = hash("sha512", $_POST['password']);
        $confirm    = hash("sha512", $_POST['confirm']);

        if ($password === $confirm)
        {
            
            $sqlite = new SQLiteDB();
            $sqlite->busyTimeout(5000);
            $sqlite->exec("PRAGMA journal_mode=WAL;");
            $result = $sqlite->query(
                "SELECT * FROM users where username = '$username';"
            );
            
            if ($result->fetchArray())
            {
                header("Location: registration.html?error=1");
            }

            $result = $sqlite->query(
                "SELECT * FROM users where email = '$email_addr';"
            );

            if ($result->fetchArray())
            {
                header("Location: registration.html?error=3");
            }
            
            $result = $sqlite->exec(
                "INSERT INTO users (
                    first_name,
                    last_name,
                    email,
                    username,
                    password,
                    last_login
                ) VALUES (
                    '$first_name',
                    '$last_name',
                    '$email_addr',
                    '$username',
                    '$password',
                    datetime(now)
                );"
            );

            if ($result)
            {
                echo "Registration successfull.<br>";
                echo "<a href=\"login.html\">Login Page</a>";
            }
            else
            {
                echo "Registration failed.<br>";
                echo "<a href=\"registration.html\">Try Again</a>";
                
            }
        }
        else
        {
            header("Location: registration.html?error=2");
        }
    }
    else
    {
        header("Location: registration.html");
    }
?>