<?php

// Load configuration as an array. Use the actual location of your configuration file
$config = parse_ini_file('../config.ini'); 

// Try and connect to the database
$connection = mysqli_connect($host['host'],$config['username'],$config['password'],$config['dbname'], $config['port']);

// If connection was not successful, handle the error
if($connection === false) {
	// Handle error - notify administrator, log to a file, show an error screen, etc.
	echo "Could not establish MySQL connection: " + mysqli_connect_error();
	
	define('connection', $connection);
	
} else {
	// Display a little database connection icon in the top right corner signifiying success
	echo "<img src=\"images/dbSuccess.png\" alt=\"Database Connected Successfully\" style=\"width:25px;height:25px;\">";
}
	
?>