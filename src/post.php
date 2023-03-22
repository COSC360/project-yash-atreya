<?php
include 'header.php';
require('db.php');
// Get query string
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    if(empty($id) || strlen(($id) == 0) || !is_numeric($id)) {
        // TODO: Display error message: Post does not exist and exit
    }
    // Get post from database
    $query = "SELECT * FROM `posts` WHERE `id` = $id";
    $result = mysqli_query($conn,$query) or die(mysql_error());
    $count = mysqli_num_rows($result);
    // Check if post exists
    if($count == 0) {
        // TODO: Display error message: Post does not exist and exit
    }
    // Get post
    $post = mysqli_fetch_assoc($result);
    // Display post
    echo "<div>";
    echo "Post title: " . $post['title'] . "<br>";
    echo "Post url: " . $post['url'] . "<br>";
    echo "Post text: " . $post['text'] . "<br>";
    echo "Post creation time: " . $post['creation_date'] . "<br>";
    echo "Post username: " . $post['username'] . "<br>";
    echo "</div>";
} else {
    // TODO:  Display error message: Post does not exist
}
?>