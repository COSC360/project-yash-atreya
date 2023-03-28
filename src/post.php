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
} else {
    // TODO:  Display error message: Post does not exist
}
?>
<!DOCTYPE html>
<html lang="en">
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
                </div>
            </div>

            <!-- Comment form -->
            <!-- <div class="card mt-4">
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <textarea class="form-control" id="comment" rows="3"></textarea>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">add comment</button>
                    </form>
                </div>
            </div> -->
        </div>
        <?php include 'footer.php'; ?>
    </body>
<html>