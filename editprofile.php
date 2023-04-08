<?php
require 'checkdisabled.php';
require 'db.php';
include 'header.php';
$logged_in = false;
if(isset($_SESSION['username']) && $_SESSION['username'] != 'root' && isset($_SESSION['user_id'])) {
    $logged_in = true;
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $user_query = "SELECT * FROM `users` WHERE `id` = $user_id";
    $result = mysqli_query($conn,$user_query) or die(mysql_error());
    $count = mysqli_num_rows($result);
    // Check if user exists
    if($count == 0) {
      echo "User with id $user_id does not exist";
      exit();
    }
    $user = mysqli_fetch_assoc($result);

    // Get user image from database
    $image_query = "SELECT user_id, username, image, content_type FROM `userImages` WHERE `user_id` = ?";
    $stmt = $conn->prepare($image_query);
    $stmt->bind_param("i", $user_id);
    $result = $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($retrieved_user_id, $retrieved_username, $image, $content_type);
    $stmt->fetch();
    mysqli_stmt_close($stmt);
} else {
    echo "You are not logged in";
    exit();
}

$changes_saved = false;
if(isset($_POST['username']) && isset($_POST['email'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $update_query = "UPDATE `users` SET `username` = '$username', `email` = '$email' WHERE `id` = $user_id";
    $result = mysqli_query($conn,$update_query) or die(mysql_error());

    if(isset($_FILES['profile_image']) && !empty($_FILES['profile_image']) && $_FILES['profile_image']['tmp_name'] != '') {
        // Check file size for sql blob type
        if($_FILES['profile_image']['size'] > 1000000) {
            echo "File size is too large";
            http_response_code(400);
            exit();
        }
        // Check file type
        $allowed_types = array('image/jpeg', 'image/png', 'image/gif', 'image/jpg');
        $content_type = mime_content_type($_FILES['profile_image']['tmp_name']);
        if(!in_array($content_type, $allowed_types)) {
            echo "File type is not allowed";
            http_response_code(400);
            exit();
        }

        // Save to uploads dir
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['profile_image']["name"]);
        move_uploaded_file($_FILES['profile_image']["tmp_name"], $target_file);
        $image = file_get_contents($target_file);
        $image_query = "UPDATE `userImages` SET `image` = ?, `content_type` = ? WHERE `user_id` = ?";
        $stmt = mysqli_stmt_init($conn); 
        mysqli_stmt_prepare($stmt, $image_query);
        $null = NULL;
        mysqli_stmt_bind_param($stmt, "bsis", $null, $content_type, $user_id);
        mysqli_stmt_send_long_data($stmt, 0, $image);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    if($result) {
        // Update session variables
        $_SESSION['username'] = $username;
        header('Location: editprofile.php?changes_saved=true');
    } else {
        echo "Error updating profile";
    }
    exit();
}

if(isset($_GET['changes_saved']) && $_GET['changes_saved'] == 'true') {
    $changes_saved = true;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>edit user</title>
    </head>
    <body>
    <div class="container mt-5">
    <h2>Edit Profile</h2>
    <?php if($changes_saved) { ?>
            <div class="alert alert-success" role="alert">
            Profile updated successfully
            </div>
     <?php } ?>
     <br>
    <form id="editProfileForm" method="post" action="editprofile.php" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <?php echo "<input type='text' class='form-control' id='username' name='username' value='" . $user['username'] . "' required>"; ?>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <?php echo "<input type='email' class='form-control' id='email' name='email' value='" . $user['email'] . "' required>"; ?>
      </div>
      <div class="mb-3">
        <p> Current Profile Image: </p>
        <?php 
        if($image != null) {
          echo '<img src="' . ($image !== null ? 'data:' . $content_type . ';base64,' . base64_encode($image) : 'path/to/default-image.png') . '" class="img-fluid rounded mx-auto mb-3" alt="Profile Image" style="max-width: 200px; max-height: 200px;">'; 
        } else {
          echo "None";
        }
        ?>
      </div>
      <div class="mb-3">
        <label for="profile_image" class="form-label">New Profile Image</label>
        <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/png, image/jpeg">
      </div>
      <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
  </div>
    <body>
</html>

