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

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['parent_id']) && isset($_POST['in_reply_to_id']) && isset($_POST['text'])) {
        $parent_id = $_POST['parent_id'];
        $in_reply_to_id = $_POST['in_reply_to_id'];
        $text = $_POST['text'];
        $user_id = $_SESSION['user_id'];
        $username = $_SESSION['username'];
        submit_comment($conn, $text, $in_reply_to_id, $user_id, $username, $parent_id);
        header("Location: post.php?id=$parent_id");
        exit();
    }
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
    if($parent_id != $in_reply_to_id) {
        $query = "UPDATE `posts` SET `comments` = `comments` + 1 WHERE `id` = $in_reply_to_id";
        $result = mysqli_query($conn,$query) or die(mysql_error());
    }
}

// Check if current user has upvoted the post
function has_upvoted($conn, $post_id, $user_id) {
    $query = "SELECT * FROM `upvotes` WHERE `post_id` = $post_id AND `user_id` = $user_id";
    $result = mysqli_query($conn,$query) or die(mysql_error());
    $count = mysqli_num_rows($result);
    if($count == 0) {
        return false;
    }
    return true;
}
if($logged_in) {
    $has_upvoted = has_upvoted($conn, $post['id'], $_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>post</title>
        <style>
            .upvote-btn {
                border: none;
                background: none;
                color: gray;
                font-size: 1rem;
                padding: 0;
                margin: 0;
                display: inline;
                padding-bottom: 0.5rem;
            }
        </style>
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

            function openReplyModal(commentId, parentCommentId) {
                console.log('opening reply modal for comment ' + commentId + ' with parent comment id ' + parentCommentId);
                const replyModal = new bootstrap.Modal(document.getElementById('replyModal'));
                document.getElementById('in_reply_to_comment_id').value = commentId;
                // Get value of in_reply_to_comment_id
                replyModal.show();
            }

            // Get comment reply buttons
            var replyButtons = document.getElementsByClassName('reply-btn');
            for (var i = 0; i < replyButtons.length; i++) {
                replyButtons[i].addEventListener('click', function(e) {
                    openReplyModal(this.getAttribute('data-comment-id'), this.getAttribute('data-parent-id'));
                });
            }
        });

    </script>
    <body>
        
        <div class="container mt-4">
        <div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="replyModalLabel">reply to comment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="reply-form" action="post.php" method="post">
                        <input type="hidden" id="in_reply_to_comment_id" name="in_reply_to_id" value="">
                        <input type="hidden" id="parent_id" name="parent_id" value="<?php echo $post['id']; ?>">
                        <div class="form-group">
                            <textarea class="form-control" id="reply" name="text" rows="3"></textarea>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">add reply</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

            <div class="card">
                <div class="card-body">
                    <!-- Upvote Button -->
                
                    <?php 
                        if($logged_in) {
                            echo "<form class='card-title' action='upvote.php' method='post'>";
                            echo "<input type='hidden' name='post_id' value='" . $post['id'] . "'>";
                            echo "<input type='hidden' name='username' value='".$username."'>";
                            echo "<input type='hidden' name='user_id' value='".$user_id."'>";
                            echo "<input type='hidden' name='from' value='post_page'>";
                            echo "<div class='d-flex'>";
                            if($has_upvoted) {
                                echo "<input type='hidden' name='increase' value='false'>";
                                echo "<button type='submit' class='upvote-btn me-2' style='color:blue;'>&#9650;</button>";
                            } else {
                                echo "<input type='hidden' name='increase' value='true'>";
                                echo "<button type='submit' class='upvote-btn me-2'>&#9650;</button>";
                            }
                            echo "<h5>" . $post['title'] . "</h5>";
                            echo "</div>";
                            echo "</form>";
                        }
                        // echo "<h5 class='card-title'>" . $post['title'] . "</h5>";
                        // Check if post has a url
                        if($post['url'] != null && $post['url'] != '') {
                            echo "<a href='" . $post['url'] . "' class='card-link'>" . $post['url'] . "</a>";
                        }
                        echo "<p class='card-text'>" . $post['text'] . "</p>";
                        echo "<div class='d-flex justify-content-between align-items-center'>";
                        echo "<span><strong>By:</strong> <a href='user.php?id=".$post['user_id']."'>" . $post['username'] . "</a></span>";
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
            <?php 
                function display_comment($conn, $comment, $indent_level, $logged_in) {
                    $indentation = ($indent_level > 0) ? 'style="margin-left:' . (40 * $indent_level) . 'px;"' : '';
                
                    echo "<div class='card'" . $indentation . ">";
                    echo "<div class='card-body'>";
                    echo "<p class='card-text'>" . $comment['text'] . "</p>";
                    echo "<div class='d-flex justify-content-between align-items-center'>";
                    if($logged_in) {
                        echo "<span class='btn btn-sm btn-primary reply-btn' data-comment-id='".$comment['id']."' data-parent-id='".$comment['parent_id']."'>reply</span>";   
                    }
                    echo "<span>by: <a href='user.php?id=".$comment['user_id']."'> " . $comment['username'] . "</a></span>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "<br>";
                    
                    // Fetch and display replies
                    $replies = get_comment_replies($conn, $comment['id']);
                    foreach ($replies as $reply) {
                        display_comment($conn, $reply, $indent_level + 1, $logged_in);
                    }
                }

                function get_comment_replies($conn, $comment_id) {
                    $query = "SELECT * FROM `posts` WHERE `in_reply_to_id` = $comment_id";
                    $result = mysqli_query($conn, $query);
                    // Check number of rows
                    $reply_count = mysqli_num_rows($result);
                    // Fetch comments
                    $replies = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    return $replies;
                }
                
                if($comment_count > 0) {
                    echo "<h6 class='mt-4'>Comments</h6>";
                    // echo "<div class='card'>";
                    // echo "<div class='card-body'>";
                    foreach($comments as $comment) {
                        // echo "<div class='card'>";
                        // echo "<div class='card-body'>";
                        // echo "<p class='card-text'>" . $comment['text'] . "</p>";
                        // echo "<div class='d-flex justify-content-between align-items-center'>";
                        // if($logged_in) {
                        //     echo "<span class='btn btn-sm btn-primary reply-btn' data-comment-id='".$comment['id']."' data-parent-id='".$comment['parent_id']."'>reply</span>";   
                        // }
                        // echo "<span>by: <a href='user.php?id=".$comment['user_id']."'> " . $comment['username'] . "</a><span>";
                        // echo "</div>";
                        // echo "</div>";
                        // echo "</div>";
                        // echo "<br>";
                        display_comment($conn, $comment, 0, $logged_in);
                    }
                    // echo "</div>";
                    // echo "</div>";
                }
            ?>
            
            
        </div>
        <?php // include 'footer.php'; ?>
    </body>
<html>