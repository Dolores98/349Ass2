<?php
    $db_host   = '192.168.2.12';
	$db_name   = 'passwordmanager';
	$db_user   = 'passuser';
	$db_passwd = 'passwordmanagerpass';
	
	$conn = mysqli_connect($db_host, $db_user, $db_passwd, $db_name);

    if(!$conn){
        die("Connection Failed: ".mysqli_connect_error());
    }