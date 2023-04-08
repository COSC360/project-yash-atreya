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

// Admin manage posts pages

if(isset($_GET['search'])) {
    $search = $_GET['search'];
    $search_query = "SELECT * FROM `posts` WHERE `title` LIKE '%$search%' OR `text` LIKE '%$search%'";
    $search_result = mysqli_query($conn,$search_query) or die(mysql_error());
    $search_count = mysqli_num_rows($search_result);
    // Check if user has posts
    $posts = array();
    if($search_count > 0) {
        while($row = mysqli_fetch_assoc($search_result)) {
            $posts[] = $row;
        }
    }
} else {
    // Get posts from database
    $posts_query = "SELECT * FROM `posts`";
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

if(isset($_POST['post_id']) && !empty($_POST['post_id']) && is_numeric($_POST['post_id']) && isset($_POST['delete_post']) &&  $_POST['delete_post'] == 'true') {
    $post_id = $_POST['post_id'];

    // Check if post exists
    $post_query = "SELECT * FROM `posts` WHERE `id` = $post_id";
    $post_result = mysqli_query($conn,$post_query) or die(mysql_error());
    $post_count = mysqli_num_rows($post_result);
    if($post_count == 0) {
        header('Location: manage_posts.php');
        exit();
    }
    // Delete upvotes where post_id = post_id
    $delete_post_upvotes_query = "DELETE FROM `upvotes` WHERE `post_id` = $post_id";
    $delete_post_upvotes_result = mysqli_query($conn,$delete_post_upvotes_query) or die(mysql_error());
    // Delete post from posts table
    $delete_post_query = "DELETE FROM `posts` WHERE `id` = $post_id";
    $delete_post_result = mysqli_query($conn,$delete_post_query) or die(mysql_error());
    // Delete posts where in_reply_to_id = post_id
    $delete_post_replies_query = "DELETE FROM `posts` WHERE `in_reply_to_id` = $post_id";
    $delete_post_replies_result = mysqli_query($conn,$delete_post_replies_query) or die(mysql_error());

    header('Location: manage_posts.php');
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>admin</title>
    <!-- <link rel="stylesheet" type="text/css" href="style.css"> -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('search-form');
            var search = document.getElementById('search');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var searchValue = search.value;
                window.location.href = "manage_posts.php?search=" + searchValue;
            });
        });
    </script>
</head>

<body>
<div class="container mt-5">
    <!-- Check if logged in -->
    <?php 
        if($adminLoggedIn == false) {
        echo "<center><p>You are not logged in, please <a href='login.php'>login here.</a><p>";
        exit();
        }
    ?>
    <form id="search-form" class="mb-4" method="get" action="manage_posts.php?search=">
        <div class="input-group">
            <input type="text" class="form-control" id="search" placeholder="search posts">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Type</th>
                <th scope="col">Title</th>
                <th scope="col">Author</th>
                <th scope="col">Upvotes</th>
                <th scope="col">Comments</th>
                <th scope="col">Action</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody id="results-tbody">
            <?php foreach($posts as $post): ?>
                <tr>
                    <td><?php echo $post['isComment'] == 1 ? 'Comment' : 'Post' ; ?></td>
                    <td><?php echo $post['title']; ?></td>
                    <td><?php echo $post['username']; ?></td>
                    <td><?php echo $post['upvotes']; ?></td>
                    <td><?php echo $post['comments']; ?></td>
                    <td><a class='btn btn-primary' href="edit_post.php?post_id=<?php echo $post['id']; ?>">Edit</a></td>
                    <td>
                        <form method="post" action="manage_posts.php">
                            <input type="hidden" name="delete_post" value="true">
                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                    
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
</body>

</html>