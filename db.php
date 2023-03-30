<?php
	$servername = "localhost";
	$username = "86003902";
	$password = "86003902";
	$dbname = "db_86003902";
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
?>