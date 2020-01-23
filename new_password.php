<!DOCTYPE html>

<html>
    
    <head>

        <title> Create a new password </title>
        <link rel="stylesheet" type="text/css" href="css/default.css">

    </head>

    <body>
        
        <div class="top_bar">
            <p class="site_title"> SITE TITLE </p>
        </div>
        <form action="" method="POST">
        <?php

            require "utils.php";

            if ($_SERVER['REQUEST_METHOD'] === 'POST')
            {
                if (!isset($_POST['username']))
                {
                    header("location: reset_password.php");
                    exit;
                }
                if (!isset($_POST['answer']))
                {
                    header("location: reset_password.php");
                    exit;
                }
                $username = filter_var(
                    $_POST['username'], FILTER_SANITIZE_STRING
                );
                $answer   = filter_var(
                    $_POST['answer'], FILTER_SANITIZE_STRING
                );
                
                echo "
<input type=\"hidden\" name=\"username\" value=\"$username\">
<input type=\"hidden\" name=\"answer\" value=\"$answer\">";
                if (isset($_POST['password']) && isset($_POST['confirm']))
                {
                    $password = $_POST['password'];
                    $confirm  = $_POST['confirm'];
                    if (strlen($password) < 10)
                    {
                        echo "<font color=\"red\">";
                        echo "<p>The password is too short. Try again</p>";
                        echo "</font>";
                    }
                    elseif ($password !== $confirm)
                    {
                        echo "<font color=\"red\">";
                        echo "<p>The password do not match. Try again</p>";
                        echo "</font>";
                    }
                    else
                    {
                        $password = hash('sha512', $password);
                        $sqlite   = new SQLiteDB();
                        $result   = $sqlite->querySingle(
                            "SELECT `answer_q`
                             FROM `users`
                             WHERE `username`='$username';"
                        );
                        
                        if (hash('sha512', $answer) === $result)
                        {
                            $sqlite->exec(
                                "UPDATE `users`
                                 SET `password`='$password'
                                 WHERE `username`='$username';"
                            );
                            echo "<p><strong>Password changed</strong></p>\n";
                            echo "<p><a href=\"login.php\">Login</a></p>\n";
                            echo "</form></body</html>";
                            exit;
                        }
                        else
                        {
                            echo "<font color=\"red\">";
                            echo "<p>Wrong answer!</p>";
                            echo "<a href=\"reset_password.php\">Try again</a>";
                            echo "</font>";
                            exit;
                        }
                    }
                }
            }
            else
            {
                header("location: reset_password.php");
                exit;
            }
            echo "
<table>
    <tr>
        <td colspan=\"2\">
            <strong>Choose a new password </strong>
        </td>
    </tr>
    <tr>
        <td>
            <br>
        </td>
    </tr>
    <tr>
        <th>
            Password:
        </th>
        <td>
            <input type=\"password\" name=\"password\">
        </td>
    </tr>
    <tr>
        <th>
            Confirm:
        </th>
        <td>
            <input type=\"password\" name=\"confirm\">
        </td>
    </tr>
    <tr>
        <td colspan=\"2\">
        <input type=\"submit\" value=\"Change Password\">
        </td>
    </tr>
</table>";
        ?>
        </form>
    </body>

</html>