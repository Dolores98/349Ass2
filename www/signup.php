<?php
    session_start();
?>
<!DOCTYPE html>
<?php
    if(isset($_SESSION['unique_username'])){
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
    <h1>Password Manager <br>Sign Up</h1>
    <form action="" method="post">
        <label>Username</label>
        <br>
        <INPUT TYPE="Text" VALUE="" NAME="dusername">
        <br>
        <br>
        <label>Password</label>
        <br>
        <INPUT TYPE="Password" VALUE="" NAME="dpassword">
        <br>
        <br>
        <INPUT TYPE="Submit" Name="Submit1" VALUE="Sign-up" id="button">
    </form>
    <br>
    <a href="login.php">Login</a>
    <?php
    //imports our connection to database server from dbconnection.php
    require 'dbconnection.php';

    //This section checks if one or both boxes are blank and notifies the user.
    if (isset($_POST['dusername']) && isset($_POST['dpassword'])) {
        if ($_POST['dusername'] === '' && $_POST['dpassword'] !== '') {
            echo 'please fill in username box';
        } elseif ($_POST['dusername'] !== '' && $_POST['dpassword'] === '') {
            echo 'please fill in password box';
        } elseif ($_POST['dusername'] === '' && $_POST['dpassword'] === '') {
            echo 'please fill in username and password box';
        }
        //If both are filled we go ahead with the database query.
        elseif ($_POST['dusername'] !== '' && $_POST['dpassword'] !== '') {

            $dusername = $_POST['dusername'];
            $dpassword = $_POST['dpassword'];

            //Instead of putting the variables straight in, we this to avoid sql injections.
            $sql = "SELECT * FROM users WHERE username = ?;";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "s", $dusername);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                //If query succesful, we check if again if the username has indeed been taken.
                if ($row = mysqli_fetch_array($result)) {
                    if ($dusername == $row['username']) {
                        echo "username has already been taken";
                    }else{
                        echo "Something went wrong";
                        exit();
                    }
                } else {
                    //If nothing was returned, then we create a new user account by inserting 
                    //the chosen username and password to the database, users table.
                    $sql = "INSERT INTO users (username, password, isroot) VALUES (?, ?, ?);";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        exit();
                    } else {
                        //users are not root, only root users can add other root users.
                        $notroot = 0;
                        mysqli_stmt_bind_param($stmt, "ssi", $dusername, $dpassword, $notroot);
                        mysqli_stmt_execute($stmt);
                        header("Location: login.php?signup=successful");
                        exit();
                    }
                }
            }
        }
    }
    ?>
</body>

</html>