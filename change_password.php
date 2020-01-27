<!DOCTYPE html>
<html>
    
    <head>
    
        <title> Change your password </title>
        <link rel="stylesheet" type="text/css" href="css/default.css">
    </head>

    <body>
        <div class="top_bar">
            <p class="site_title"> SITE TITLE </p>
        </div>
        <br>
        <br>
            <form action="" method="POST">
                <?php
                    require "utils.php";
                    session_start();
                    remove_duplicate_cookies();
                    if (isset($_SESSION['loggedin']) &&
                        ($_SESSION['loggedin'] === true))
                    {
                        if ($_SERVER['REQUEST_METHOD'] === 'POST')
                        {
                            $user_id  = $_SESSION['id'];
                            $old_pass = $_POST['old_password'];
                            $new_pass = $_POST['new_password'];
                            $confirm  = $_POST['confirm'];
                            if (strlen($new_pass) < 10)
                            {
                                echo "
<p class=\"alert\">The password is too short.</p>";
                            }
                            else if ($new_pass !== $confirm)
                            {
                                echo "
<p class=\"alert\">Passwords don't match.</p>";
                            }
                            else
                            {
                                $hashed_pwd  = hash("sha512", $new_pass);
                                $sqlite      = new SQLiteDB();
                                $current_pwd = $sqlite->querySingle(
                                    "SELECT `password`
                                     FROM users
                                     WHERE `id`='$user_id';"
                                );
                                if (hash('sha512', $old_pass) === $current_pwd)
                                {
                                    $sqlite->exec(
                                        "UPDATE `users`
                                         SET `password` = '$hashed_pwd'
                                         WHERE `id`='$user_id';"
                                         );
                                    header("location: home.php");
                                }
                                else
                                {
                                    echo "
<p class=\"alert\">Wrong password.</p>";
                                }
                            }
                        }
                    }
                    else
                    {
                        header("location: login.php");
                    }
                ?>
                <table>
                <tr>
                    <th>
                        Old Password:
                    </th>
                    <td>
                        <input name="old_password" type="password">
                    </td>
                </tr>
                <tr>
                    <th>
                        New Password:
                    </th>
                    <td>
                        <input name="new_password" type="password">
                    </td>
                </tr>
                <tr>
                    <th>
                        Confirm:
                    </th>
                    <td>
                        <input name="confirm" type="password">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <br>
                        <input name="bt_login" type="submit" value="Change">
                    </td>
                </tr>
            </table>
        </form>
    </body>

</html>