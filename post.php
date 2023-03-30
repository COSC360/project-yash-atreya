<?php
require 'checkdisabled.php';
require('db.php');
// Check if user is logged in using the session variable
include 'header.php';   
$logged_in = false;
if(isset($_SESSION['username']) && $_SESSION['username'] != 'root' && isset($_SESSION['user_id'])) {
    // header("Location: login.php");
    $logged_in = true;
}

if(isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['text']) && !empty($_GET['text']) && $logged_in && isset($_GET['add_comment']) && $_GET['add_comment'] == 'true') {
    // Check if the text is empty
    // TODO: Add hard id validation checks. Check if the id is a number and if it exists in the database
    $text = $_GET['text'];
    $in_reply_to_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $parent_id = $_GET['id'];
    submit_comment($conn, $text, $in_reply_to_id, $user_id, $username, $parent_id);
    // header("Location: post.php?id=$_GET[id]");
}
// Get query string
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    if(empty($id) || strlen(($id) == 0) || !is_numeric($id)) {
        echo "Post does not exist, specify a valid post id in the url query string like this <a href='post.php?id=2'> post.php?id=2 </a>";
        $id = null;
        exit();
    }
    // Get post from database
    $query = "SELECT * FROM `posts` WHERE `id` = $id";
    $result = mysqli_query($conn,$query) or die(mysql_error());
    $count = mysqli_num_rows($result);
    // Check if post exists
    if($count == 0) {
        // TODO: Display error message: Post does not exist and exit
        echo "Post with id $id does not exist";
        $id = null;
        exit();
    }
    // Get post
    $post = mysqli_fetch_assoc($result);

    // Fetch comments
    // TODO: Add order by upvotes
    $query = "SELECT * FROM `posts` WHERE `isComment` = 1 AND `in_reply_to_id` = $id";
    $comment_result = mysqli_query($conn,$query) or die(mysql_error());
    $comment_count = mysqli_num_rows($comment_result);
    $comments = array();
    while($row = mysqli_fetch_assoc($comment_result)) {
        $comments[] = $row;
    }
} else {
    echo "Bad request, specify a valid post id in the url query string like this <a href='post.php?id=2'> post.php?id=2 </a>";
    $id = null;
    exit();
}

function submit_comment($conn, $text, $in_reply_to_id, $user_id, $username, $parent_id) {
    // Submit a query by inserting a new comment with values of text, in_reply_to_id, user_id, username, parent_id
    $query = 'INSERT INTO `posts` (`text`, `in_reply_to_id`, `user_id`, `username`, `parent_id`, `isComment`) VALUES (?, ?, ?, ?, ?, ?)';
    $stmt = $conn->prepare($query);
    // Print the query
    $isComment = 1;
    $stmt->bind_param('siisii', $text, $in_reply_to_id, $user_id, $username, $parent_id, $isComment);
    $result = $stmt->execute();
    // Update the parent post's comment count
    $query = "UPDATE `posts` SET `comments` = `comments` + 1 WHERE `id` = $parent_id";
    $result = mysqli_query($conn,$query) or die(mysql_error());
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>post</title>
    </head>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('comment-form');
            form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('submitting comment...');
            // form.setAttribute('action', 'admin.php?search=' + document.getElementById('search').value);
            let params = new URL(document.location).searchParams;
            let id = params.get('id');
            window.location.href = 'post.php?id=' + id + '&text=' + document.getElementById('comment').value + '&add_comment=true';
            // e.preventDefault();
            });
        });
    </script>
    <body>
        <div class="container mt-4">
            <div class="card">
                <div class="card-body">
                    <?php 
                        echo "<h5 class='card-title'>" . $post['title'] . "</h5>";
                        // Check if post has a url
                        if($post['url'] != null && $post['url'] != '') {
                            echo "<a href='" . $post['url'] . "' class='card-link'>" . $post['url'] . "</a>";
                        }
                        echo "<p class='card-text'>" . $post['text'] . "</p>";
                        echo "<div class='d-flex justify-content-between align-items-center'>";
                        echo "<span><strong>Upvotes:</strong> " . $post['upvotes'] . "</span>";
                        echo "<span><strong>Comments:</strong> " . $post['comments'] . "</span>";
                        echo "</div>";
                    ?>
                    <br>
                    <form id="comment-form">
                        <div class="form-group">
                            <textarea class="form-control" id="comment" rows="3"></textarea>
                        </div>
                        <br>
                        <?php if($logged_in) { ?>
                            <button type="submit" class="btn btn-primary">add comment</button> <?php } else { ?>
                            <a href="login.php" class="btn btn-primary">login to comment</a> <?php } ?>
                    </form>
                </div>
            </div>

            <!-- Comment form -->
            <?php 
                if($comment_count > 0) {
                    echo "<h6 class='mt-4'>Comments</h6>";
                    // echo "<div class='card'>";
                    // echo "<div class='card-body'>";
                    foreach($comments as $comment) {
                        echo "<div class='card'>";
                        echo "<div class='card-body'>";
                        echo "<p class='card-text'>" . $comment['text'] . "</p>";
                        echo "<div class='d-flex justify-content-between align-items-center'>";
                        // echo "<span><strong>Upvotes:</strong> " . $comment['upvotes'] . "</span>";
                        // echo "<span><strong>Comments:</strong> " . $comment['comments'] . "</span>";
                        echo "<span>by: <a href='user.php?id=".$comment['user_id']."'> " . $comment['username'] . "</a><span>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                        echo "<br>";
                    }
                    // echo "</div>";
                    // echo "</div>";
                }
            ?>
            
        </div>
        <?php // include 'footer.php'; ?>
    </body>
<html>