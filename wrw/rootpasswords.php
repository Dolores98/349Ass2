<?php
//Need to start a session in each page.
session_start();
?>
<!DOCTYPE html>
<?php
//Determines if a user is not logged in which case, they will be redirected to the login page.
if (!isset($_SESSION['root_level'])) {
    header("Location: rootlogin.php?please_login");
    exit();
}

?>
<html lang="en">

<head>
    <title>Root Manager</title>
    <link rel="stylesheet" href="rootstyle.css">
    <link rel="icon" href="icon.png">
</head>

<body>
    <h1>Password Database<br>Manager</h1>
    <label id='privone'>(level 1 root users)</label>
    <form action="" method="post">
        <input type="Submit" name="useraccounts" value="Show User Accounts" id="showtable">
    </form>
    <label id='privtwo'>(level 2 root users only)</label>
    <form action="" method="post">
        <label id="querylabel">Place Query Here</label>
        <br>
        <textarea rows="10" cols="41" name="query"></textarea>
        <br>
        <br>
        <INPUT TYPE="Submit" Name="Sendbutton" VALUE="Send" id="button">
    </form>
    <br>
    <a href="rootlogout.php">Sign out</a>
</body>
<?php
require 'rootdbconnection.php';

if (isset($_POST['query']) && $_POST['query'] !== '' && $_SESSION['root_level'] == 2) {
    $query = $_POST['query'];
    $sql = "$query";
    $result = mysqli_query($conn, $sql);

    echo "<div id='table-wrapper'>";
    echo "<div id='table-scroll'>";
    echo '<table>';
    while ($row = mysqli_fetch_array($result)) {
        echo '<tr>';
        for ($i = 0; $i < count($row)/2; $i++) {
            echo '<td>' . $row[$i] . "</td>";
        }
        echo '<tr>';
    }
    echo '</table>';
    echo '</div>';
    echo '</div>';
}elseif(isset($_POST['useraccounts']) && $_POST['useraccounts'] !== ''){
    $sql = "SELECT * FROM useraccounts;";
    $result = mysqli_query($conn, $sql);

    echo "<div id='table-wrapper'>";
    echo "<div id='table-scroll'>";
    echo '<table>';
    echo "<tr id='columns'><td>website</td><td>username</td><td>passwd</td><td>owner</td></tr>";
    while ($row = mysqli_fetch_array($result)) {
        echo '<tr>';
        for ($i = 0; $i < count($row)/2; $i++) {
            echo '<td>' . $row[$i] . "</td>";
        }
        echo '<tr>';
    }
    echo '</table>';
    echo '</div>';
    echo '</div>';
}
?>

</html>