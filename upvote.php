<?php
require 'db.php';
// Check if Post request is made
if($_SERVER['REQUEST_METHOD'] != 'POST') {
  echo 'Invalid request, only accepts POST requests';
  http_response_code(405);
  exit();
}

if(!isset($_POST['post_id']) || !isset($_POST['user_id']) || !isset($_POST['username'])) {
  echo 'Bad Data';
  http_response_code(400);
  exit();
}

$post_id = $_POST['post_id'];
$user_id = $_POST['user_id'];
$username = $_POST['username'];
// Increase or decrease upvote
if(isset($_POST['increase']) && $_POST['increase'] == 'true') {
  increaseUpvote($conn, $post_id, $user_id, $username);
} else if(isset($_POST['increase']) && $_POST['increase'] == 'false') {
  decreaseUpvote($conn, $post_id, $user_id, $username);
} else {
  echo 'Bad Data';
  http_response_code(400);
  exit();
}


function increaseUpvote($conn, $post_id, $user_id, $username) {
    // Check if already upvoted by user
    $query = "SELECT * FROM `upvotes` WHERE `post_id` = $post_id AND `user_id` = $user_id";
    try {
        $result = mysqli_query($conn,$query);
        $count = mysqli_num_rows($result);
        if($count > 0) {
            echo 'Already upvoted';
            http_response_code(200);
            exit();
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
        http_response_code(500);
        exit();
    }

    // Increase upvote
    $query = "UPDATE `posts` SET `upvotes` = `upvotes` + 1 WHERE `id` = $post_id";
    try {
        $result = mysqli_query($conn,$query);
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
        http_response_code(500);
        exit();
    }

    // Add to upvote table
    $query = "INSERT INTO `upvotes` (`post_id`, `user_id`, `username`) VALUES ($post_id, $user_id, '$username')";
    try {
        $result = mysqli_query($conn,$query);
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
        http_response_code(500);
        exit();
    }

    echo 'Upvote increased';
    if(isset($_POST['from']) && $_POST['from'] == 'post_page') {
        header('Location: post.php?id=' . $post_id);
        exit();
    }
    http_response_code(200);
    exit();
}

function decreaseUpvote($conn, $post_id, $user_id, $username) {
    // Check if user has upvoted
    $query = "SELECT * FROM `upvotes` WHERE `post_id` = $post_id AND `user_id` = $user_id";
    try {
        $result = mysqli_query($conn,$query);
        $count = mysqli_num_rows($result);
        if($count == 0) {
            echo 'User has not upvoted';
            // http_response_code(400);
            exit();
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
        http_response_code(500);
        exit();
    }
    // Check if upvotes are already zero
    $query = "SELECT `upvotes` FROM `posts` WHERE `id` = $post_id";
    try {
        $result = mysqli_query($conn,$query);
        $row = mysqli_fetch_assoc($result);
        if($row['upvotes'] == 0) {
            echo 'Upvotes are already zero';
            http_response_code(400);
            exit();
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
        http_response_code(500);
        exit();
    }
    // Decrease upvote
    $query = "UPDATE `posts` SET `upvotes` = `upvotes` - 1 WHERE `id` = $post_id";
    try {
    $result = mysqli_query($conn,$query);
    } catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    http_response_code(500);
    exit();
    }

    // Remove from upvote table
    $query = "DELETE FROM `upvotes` WHERE `post_id` = $post_id AND `user_id` = $user_id";
    try {
    $result = mysqli_query($conn,$query);
    } catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    http_response_code(500);
    exit();
    }

    echo 'Upvote decreased';
    if(isset($_POST['from']) && $_POST['from'] == 'post_page') {
        header('Location: post.php?id=' . $post_id);
        exit();
    }
    http_response_code(200);
    exit();
}

?>