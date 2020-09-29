<?php
//Need to start a session in each page.
session_start();
?>
<!DOCTYPE html>
<?php
//Determines if a user is not logged in which case, they will be redirected to the login page.
if (!isset($_SESSION['unique_username'])) {
    header("Location: login.php?please_login");
    exit();
}
?>
<html lang="en">

<head>
    <title>Password Manager</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="icon.png">
</head>

<body>
    <h1>Password Manager</h1>
    <!--
        The first form allows users to add an account to the password manager database.
    -->
    <p>Fill in the following three fields to add
    <br> an account to your Password Manager</p>
    <form action="" method="post">
        <label>Website or Application Name</label>
        <br>
        <input type="hidden" name="step" value="1">
        <INPUT TYPE="Text" VALUE="" NAME="dwebsite">
        <br>
        <br>
        <label>Username</label>
        <br>
        <INPUT TYPE="Text" VALUE="" NAME="dusername">
        <br>
        <br>
        <label>Password</label>
        <br>
        <INPUT TYPE="Text" VALUE="" NAME="dpassword">
        <br>
        <br>
        <INPUT TYPE="Submit" Name="Submit" VALUE="Add" id="button">
    </form>
    <?php
    require 'dbconnection.php';
    if (isset($_POST['step']) && $_POST['step'] == 1) {
        if (isset($_POST['dwebsite']) && isset($_POST['dusername']) && isset($_POST['dpassword'])) {
            //All boxes are filled.
            if ($_POST['dwebsite'] !== '' && $_POST['dusername'] !== '' && $_POST['dpassword'] !== '') {

                $websiteone = $_POST['dwebsite'];
                $usernameone = $_POST['dusername'];
                $password = $_POST['dpassword'];

                //We use a prepared statement for the sql query to prevent injections.
                $sql = "SELECT * FROM useraccounts WHERE accwebsite = ? AND accusername = ? AND accpassword = ? AND username = ?;";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo "Hello0";
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, "ssss", $websiteone, $usernameone, $password, $_SESSION['unique_username']);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($row = mysqli_fetch_array($result)) {
                        //If the query returned something, we check if the account already exists.
                        if ($websiteone == $row['accwebsite'] && $usernameone == $row['accusername'] &&
                            $password == $row['accpassword'] && $_SESSION['unique_username'] == $row['username']) {
                            echo "account is already saved";
                        } else {
                            echo "There has been an error";
                            exit();
                        }
                    } else {
                        //If nothing was returned from our query, then the account does not exist, and it gets inserted to the useraccounts table.
                        $sql = "INSERT INTO useraccounts (accwebsite, accusername, accpassword, username) VALUES (?, ?, ?, ?);";
                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            exit();
                        } else {
                            mysqli_stmt_bind_param($stmt, "ssss", $websiteone, $usernameone, $password, $_SESSION['unique_username']);
                            mysqli_stmt_execute($stmt);
                            echo "account has been added";
                        }
                    }
                }
            }else{
                echo "please fill in all the boxes";
            }
        }
    }

    ?>
    <br>
    <!--
        The second form allows users to retrieve an account password from the database.
    -->
    <p>Fill in the following two fields <br>to retrieve password</p>
    <form action="" method="post">
        <label>Website</label>
        <br>
        <input type="hidden" name="step" value="2">
        <INPUT TYPE="Text" VALUE="" NAME="dwebsite">
        <br>
        <br>
        <label>Username</label>
        <br>
        <INPUT TYPE="Text" VALUE="" NAME="dusername">
        <br>
        <br>
        <INPUT TYPE="Submit" Name="Submit" VALUE="Retrieve" id="button">
    </form>
    <br>
    <a href="logout.php">Sign out</a>
    <?php
    require 'dbconnection.php';
    if (isset($_POST['step']) && $_POST['step'] == 2) {
        if (isset($_POST['dwebsite']) && isset($_POST['dusername'])) {
            //Both boxes are filled;
            if ($_POST['dwebsite'] !== '' && $_POST['dusername'] !== '') {

                $websitetwo = $_POST['dwebsite'];
                $usernametwo = $_POST['dusername'];

                //We use a prepared statement for the sql query to prevent injections.
                $sql = "SELECT * FROM useraccounts WHERE accwebsite = ? AND accusername = ? AND username = ?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, "sss", $websitetwo, $usernametwo, $_SESSION['unique_username']);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    //If our query returned something, we double check if the account matches, then we reveal account password,
                    //othere wise we notify the user that the account does not exist.
                    if ($row = mysqli_fetch_array($result)) {
                        if ($websitetwo == $row['accwebsite'] && $usernametwo == $row['accusername'] && $_SESSION['unique_username'] == $row['username']) {
                            $passwd = $row['accpassword'];
                            echo "<label>Password: " . $passwd . "</label>";
                            exit();
                        } else {
                            echo "account does not exist";
                            exit();
                        }
                    } else {
                        echo "account does not exist";
                        exit();
                    }
                }
            }else{
                echo "please fill in both boxes";
            }
        }
    }

    ?>
</body>

</html>