<?php
include 'header.php';
require('db.php');
// Get query string
$id = get_user_id();
if($id == -1) {
    // TODO: Display error message: User does not exist and exit
} else {
    // Get user from database
    $query = "SELECT * FROM `users` WHERE `id` = $id";
    $result = mysqli_query($conn,$query) or die(mysql_error());
    $count = mysqli_num_rows($result);
    // Check if user exists
    if($count == 0) {
        // TODO: Display error message: User does not exist and exit
    }
    // Get user
    $user = mysqli_fetch_assoc($result);
    // Display user
    echo "<div>";
    echo "username: " . $user['username'] . "<br>";
    echo "email: " . $user['email'] . "<br>";
    echo "creation time: " . $user['creation_date'] . "<br>";
    echo "</div>";
    if(isset($_SESSION['username']) && $_SESSION['username'] == $user['username']) {
        // logout button
        echo "<a href='logout.php'>Logout</a>";
    }
}

function get_user_id() {
    if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
        return $_GET['id'];
    } else {
        return -1;
    }
}
?>