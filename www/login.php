<?php
session_start();
?>
<!DOCTYPE html>
<?php
if (isset($_SESSION['unique_username'])) {
    header("Location: passwords.php?currently_logged_in");
}
?>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>PASSWORD login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="icon.png">
</head>

<body>
    <h1>Password Manager <br>Login</h1>
    <form action="" method="post">
        <label>Username</label>
        <br>
        <INPUT TYPE="Text" VALUE="" NAME="username">
        <br>
        <br>
        <label>Password</label>
        <br>
        <INPUT TYPE="password" VALUE="" NAME="dpassword">
        <br>
        <br>
        <INPUT TYPE="Submit" Name="Submit1" VALUE="Login" id="button">
    </form>
    <br>
    <a href="signup.php">Sign Up</a>
    <?php
    //imports our connection to database server from dbconnection.php
    require 'dbconnection.php';

    //This section checks if one or both boxes are blank and notifies the user.
    if (isset($_POST['username']) && isset($_POST['dpassword'])) {
        if ($_POST['username'] === '' && $_POST['dpassword'] !== '') {
            echo 'please fill in username box';
        } elseif ($_POST['username'] !== '' && $_POST['dpassword'] === '') {
            echo 'please fill in password box';
        } elseif ($_POST['username'] === '' && $_POST['dpassword'] === '') {
            echo 'please fill in username and password box';
        }
        //If both are filled we go ahead with the database query.
        elseif ($_POST['username'] !== '' && $_POST['dpassword'] !== '') {
            $dusername = $_POST['username'];
            $dpassword = $_POST['dpassword'];

            //Instead of putting the variables straight in, we this to avoid sql injections.
            $sql = "SELECT * FROM users WHERE username = ? && password = ?;";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $dusername, $dpassword);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                //If query succesful, we check again just incase, then we start a session.
                if ($row = mysqli_fetch_array($result)) {
                    if ($dpassword == $row['password'] && $dusername == $row['username'] && $row['isroot'] == 0) {
                        session_start();
                        $_SESSION['unique_username'] = $row['username'];
                        header('Location: passwords.php?Login=successful');
                        exit();
                    }
                    else {
                        echo "invalid username or password";
                        exit();
                    }
                } else {
                    echo "invalid username or password";
                    exit();
                }
            }
        }
    }
    ?>
</body>

</html>