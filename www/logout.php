<?php
    session_start();
    unset($_SESSION['unique_username']);
    session_destroy();
    header("Location: login.php?logged_out=successful");
    exit();