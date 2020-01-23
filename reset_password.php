<!DOCTYPE html>

<html>
    <head>
        
        <title> Reset your password </title>
        <link rel="stylesheet" type="text/css" href="css/default.css">
    </head>

    <body>
        <div class="top_bar">
            <p class="site_title"> SITE TITLE </p>
        </div>
        <br>
        <br>
        <?php
            require "utils.php";
            if (($_SERVER['REQUEST_METHOD'] === 'POST'))
            {
                if (isset($_POST['username']))
                {
                    $username = filter_var(
                        $_POST['username'], FILTER_SANITIZE_STRING
                    );
                    $sqlite = new SQLiteDB();
                    $result = $sqlite->querySingle(
                        "SELECT * FROM users WHERE `username`='$username';",
                        true
                    );
                    if ($result)
                    {
                        $question = $result['security_q'];
                        echo "
        <form action=\"new_password.php\" method=\"POST\">
            <input type=\"hidden\" name=\"username\" value=\"$username\">
            <table>
                <tr>
                    <td colspan=\"2\">
                        <strong>Question: </strong>$question
                    </td>
                </tr>
                <tr>
                    <td>
                        <br>
                    </td>
                </tr>
                <tr>
                    <th>
                        Answer:
                    </th>
                    <td>
                        <input type=\"text\" name=\"answer\">
                    </td>
                </tr>
                <tr>
                    <td colspan=\"2\">
                    <input type=\"submit\" value=\"Recover\">
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>";
                        exit;
                    }
                }
            }
        ?>

        <form action="" method="POST">
            <table>
                <tr>
                    <th>
                        Username:
                    </th>
                    <td>
                        <input name="username" type="text">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <br>
                        <input name="bt_login" type="submit" value="Recover">
                    </td>
                </tr>
            </table>
        </form>
    </body>

</html>