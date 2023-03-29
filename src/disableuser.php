<?php
require 'checkdisabled.php';
require 'db.php';
session_start();
// Admin variables
$admin_username = null;
$admin_user_id = null;
if(isset($_SESSION['username']) && isset($_SESSION['user_id']) && $_SESSION['username'] != 'root') {
    $admin_username = $_SESSION['username'];
    $admin_user_id = $_SESSION['user_id'];

    // Check if the logged in user is an admin
    $query = "SELECT * FROM `users` WHERE username='$admin_username'";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    $user_id = $row['id'];
    $admin = $row['isAdmin'];
    if($admin != 1) {
        $admin_username = null;
        $admin_user_id = null;
        echo "<center><div>You are not an admin</div></center>";
        exit();
    }
}
// Check request method is post
if($_SERVER['REQUEST_METHOD'] == 'POST' && $admin_username != null) {
    $disable_user_id = $_POST['disable_user_id'] ? $_POST['disable_user_id'] : null;
    $query = "UPDATE `users` SET `isDisabled` = '1' WHERE `id` = $disable_user_id";
    $result = mysqli_query($conn,$query);
    if($result) {
        echo "User disabled";
        header("admin.php");
    } else {
        echo "Error disabling user";
        exit();
    }
} else {
    echo "Bad Request";
    header("admin.php");
}
?>