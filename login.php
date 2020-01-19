<?php
    
    class SQLiteDB extends SQLite3
    {
        function __construct()
        {
            $this->open("db/mydatabase.db");
        }
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
            TRUE
        );
        
        if ($user_info)
        {
            $id = $user_info['id'];
            $sqlite->exec(
                "UPDATE users
                 SET `last_login` = datetime('now')
                 WHERE `id` = '$id';");
            echo "You're logged. ";
            echo "But there's nothing more to see for now... sorry :p<br>";
            foreach ($user_info as $key => $value)
            {
                echo "<p> $key: $value </p>";
            }
        }
        else
        {
            header("Location: login.html?error=2");
        }
    }
    else
    {
        header("Location: login.html");
    }
?>