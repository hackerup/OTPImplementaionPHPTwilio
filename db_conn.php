<?php

$configVariables = include ('config.php');

$dbUserName = $configVariables['db_username'];
$dbPassword = $configVariables['db_password'];
$dbName = $configVariables['db_name'];

$connection = mysqli_connect('localhost', $dbUserName, $dbPassword, $dbName) or die('could not connect to dataabase');

