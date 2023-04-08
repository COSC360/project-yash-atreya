<?php
require 'db.php';
include 'header.php';
// Admin variables
$adminLoggedIn = false;
$adminUsername = null;

if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $query = "SELECT * FROM `users` WHERE username='$username'";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    $user_id = $row['id'];
    $admin = $row['isAdmin'];
    if($admin == 1) {
        $adminLoggedIn = true;
        $adminUsername = $username;
    } else {
        $adminLoggedIn = false;
        $adminUsername = null;
        echo "<center><div>You are not an admin</div></center>";
        exit();
    }
} else {
    $adminLoggedIn = false;
    $adminUsername = null;
}

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset($_GET['post_id']) && !empty($_GET['post_id']) && is_numeric($_GET['post_id'])) {
        $post_id = $_GET['post_id'];
        // Get post
        $post_query = "SELECT * FROM `posts` WHERE `id` = $post_id";
        $post_result = mysqli_query($conn,$post_query) or die(mysql_error());
        $post_count = mysqli_num_rows($post_result);
        // Check if post exists
        if($post_count == 0) {
            echo "<center><div>Post does not exist</div></center>";
            exit();
        } else {
            $post = mysqli_fetch_assoc($post_result);
        }
    } else {
        echo "<center><div>Bad data</div></center>";
        exit();
    }
}
// Edit post
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['post_id']) && !empty($_POST['post_id']) && is_numeric($_POST['post_id'])) {
        $post_id = $_POST['post_id'];
        // Get post
        $post_query = "SELECT * FROM `posts` WHERE `id` = $post_id";
        $post_result = mysqli_query($conn,$post_query) or die(mysql_error());
        $post_count = mysqli_num_rows($post_result);
        // Check if post exists
        if($post_count == 0) {
            echo "<center><div>Post does not exist</div></center>";
            exit();
        } else {
            $post = mysqli_fetch_assoc($post_result);
        }

        if(isset($_POST['text']) && !empty($_POST['text'])) {
            $title = $_POST['title'];
            $url = $_POST['url'];
            $text = $_POST['text'];
            // Update post
            $update_post_query = "UPDATE `posts` SET `title` = ?, `url` = ?, `text` = ? WHERE `id` = ?";
            $stmt = $conn->prepare($update_post_query);
            $stmt->bind_param("sssi", $title, $url, $text, $post_id);
            $update_post_result = $stmt->execute();
            $stmt->close();
            if($update_post_result) {
                echo "<center><div>Post updated</div></center>";
                header("Location: manage_posts.php");
                exit();
            } else {
                echo "<center><div>Error updating post</div></center>";
                exit();
            }
        } else {
            echo "<center><div>Bad data</div></center>";
            exit();
        }
    } else {
        echo "<center><div>Bad data</div></center>";
        exit();
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Edit Post</title>
</head>
<body>
    <!-- Edit Post Form -->
    <div class="container mt-4">
        <h2>Edit Post</h2>
        <div class="card"> 
            <form class="card-body" action="edit_post.php" method="post">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <!-- If comment display only text -->
                <?php
                if($post['isComment'] == 1) {
                    echo "<div class='form-group'>
                            <label for='text'>Text</label>
                            <textarea name='text' class='form-control' rows='5'>".$post['text']."</textarea>
                        </div>
                        <br>
                        <button type='submit' class='btn btn-primary'>Save Changes</button>";
                    exit();
                } ?>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" class="form-control" value="<?php echo $post['title']; ?>">
                </div>
                <br>
                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="text" name="url" class="form-control" value="<?php echo $post['url']; ?>">
                </div>
                <br>
                <div class="form-group">
                    <label for="text">Text</label>
                    <textarea name="text" class="form-control" rows="5"><?php echo $post['text']; ?></textarea>
                </div>
                <br>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
</body>
</html>
