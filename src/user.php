<?php
require 'checkdisabled.php';
require('db.php');
include 'header.php';
// Get query string
$id = get_user_id();
if($id == -1) {
    // TODO: Display error message: User does not exist and exit
    echo "User does not exist, specify a valid user id in the url query string like this <a href='user.php?id=2'> user.php?id=2 </a>";
    exit();
}
// Get user from database
$user_query = "SELECT * FROM `users` WHERE `id` = $id";
$result = mysqli_query($conn,$user_query) or die(mysql_error());
$user = mysqli_fetch_assoc($result);
$count = mysqli_num_rows($result);
// Check if user exists
if($count == 0) {
  echo "User with id $id does not exist";
  exit();
}

$posts_param = isset($_GET['posts']) && !empty($_GET['posts'] && $_GET['posts'] == 'true') ? $_GET['posts'] : null;

if($posts_param) {
    // Get posts from database
    $posts_query = "SELECT * FROM `posts` WHERE `user_id` = $id AND `isComment` = 0";
    $posts_result = mysqli_query($conn,$posts_query) or die(mysql_error());
    $posts_count = mysqli_num_rows($posts_result);
    // Check if user has posts
    $posts = array();
    if($posts_count > 0) {
        while($row = mysqli_fetch_assoc($posts_result)) {
            $posts[] = $row;
        }
    }
}

$comments_param = isset($_GET['comments']) && !empty($_GET['comments'] && $_GET['comments'] == 'true') ? $_GET['comments'] : null;

if($comments_param) {
    // Get comments from database
    $comments_query = "SELECT * FROM `posts` WHERE `user_id` = $id AND `isComment` = 1";
    $comments_result = mysqli_query($conn,$comments_query) or die(mysql_error());
    $comments_count = mysqli_num_rows($comments_result);
    // Check if user has comments
    $comments = array();
    if($comments_count > 0) {
        while($row = mysqli_fetch_assoc($comments_result)) {
            $comments[] = $row;
        }
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
<!DOCTYPE html>
<html>
    <head>
        <title>user</title>
    </head>
    <body>
    <div class="container">
    <div class="row">
      <div class="col-12">
        <?php 
          if ($posts_param == null && $comments_param == null) {
            include 'profile.php';
          }
          if ($posts_param == 'true') {
            include 'user_posts.php';
          } else if ($comments_param == 'true') {
            include 'user_comments.php';
          }
        ?>
    </div>
    </div>
</body>
</html>