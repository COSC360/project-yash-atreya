<?php
// Logout user
session_start();
// Unset all of the session variables
session_unset();
// Destroy the session
session_destroy();
// Check if the username variable is unset
print_r($_SESSION['username']);
// if(!isset($_SESSION['username'])) {
//     // Redirect to login page
//     print_r($_SESSION['username']);
//     header("Location: login.php");
// }
?>