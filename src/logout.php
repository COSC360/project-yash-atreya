<?php
include 'db.php';
// Logout user
session_start();
$user_id = $_SESSION['user_id'];
// Update the logged in status
$query = "UPDATE `users` SET `logged_in` = 0 WHERE `id` = $user_id";
$result = mysqli_query($conn,$query) or die(mysql_error());
// Unset all of the session variables
session_unset();
// Destroy the session
session_destroy();

// Redirect to login page
header("Location: index.php");
?>