<?php
    /*
     * PHP looks a little like Perl... I like that :)
     */
    
    require "utils.php";

    if (isset($_POST['bt_submit']))
    {
        $email_addr = filter_var(
            $_POST['email_address'], FILTER_SANITIZE_EMAIL
        );

        if (!$email_addr)
        {
            header("Location: registration.html?error=0");
        }
        $s_question = filter_var($_POST['s_question'], FILTER_SANITIZE_STRING);
        $first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
        $last_name  = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
        $username   = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $s_answer   = $_POST['s_answer'];

        if (strlen($username) < 5)
        {
            header("Location: registration.html?error=5");
        }

        if (strlen($s_question) < 5)
        {
            header("Location: registration.html?error=6");
        }

        if (strlen($s_answer) < 2)
        {
            header("Location: registration.html?error=7");
        }

        if (strlen($_POST['password']) < 10)
        {
            header("Location: registration.html?error=4");
        }
        
        $password   = hash("sha512", $_POST['password']);
        $confirm    = hash("sha512", $_POST['confirm']);
        $answer     = hash("sha512", $s_answer);

        if ($password === $confirm)
        {
            
            $sqlite = new SQLiteDB();
            $sqlite->busyTimeout(5000);
            $sqlite->exec("PRAGMA journal_mode=WAL;");
            $result = $sqlite->query(
                "SELECT * FROM users where `username` = '$username';"
            );
            
            if ($result->fetchArray())
            {
                header("Location: registration.html?error=1");
            }

            $result = $sqlite->query(
                "SELECT * FROM users where `email` = '$email_addr';"
            );

            if ($result->fetchArray())
            {
                header("Location: registration.html?error=3");
            }
            
            $result = $sqlite->exec(
                "INSERT INTO users (
                    `first_name`,
                    `last_name`,
                    `email`,
                    `username`,
                    `password`,
                    `last_login`,
                    `security_q`,
                    `answer_q`
                ) VALUES (
                    '$first_name',
                    '$last_name',
                    '$email_addr',
                    '$username',
                    '$password',
                    'never',
                    '$s_question',
                    '$answer'
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