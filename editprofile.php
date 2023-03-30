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
    <form id="editProfileForm" method="post" action="editprofile.php">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <!-- <input type="text" class="form-control" id="username" name="username" value= required> -->
        <?php echo "<input type='text' class='form-control' id='username' name='username' value='" . $user['username'] . "' required>"; ?>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <!-- <input type="email" class="form-control" id="email" name="email" required> -->
        <?php echo "<input type='email' class='form-control' id='email' name='email' value='" . $user['email'] . "' required>"; ?>
      </div>
      <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
  </div>
    <body>
</html>