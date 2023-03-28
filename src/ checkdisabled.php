<?php
	require 'db.php';
	session_start();
	$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
	$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
	// Check if user is disabled
	$query = "SELECT * FROM `users` WHERE `id` = $user_id";
	$result = mysqli_query($conn,$query) or die(mysql_error());
	$user = mysqli_fetch_assoc($result);
	$isDiabled = $user['isDisabled'];
	
	if($isDiabled == 1){
		echo "Your account is disabled";
		exit();
	}
?>