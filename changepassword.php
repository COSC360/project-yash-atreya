<?php
require 'checkdisabled.php';
require 'db.php';
include 'header.php';
$logged_in = false;
if(isset($_SESSION['username']) && $_SESSION['username'] != 'root' && isset($_SESSION['user_id'])) {
    $logged_in = true;
}
if(!$logged_in) {
    echo "You are not logged in";
    exit();
}
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$no_match = false;
if(isset($_POST['oldPassword']) && isset($_POST['newPassword'])) {
    $oldpassword = $_POST['oldPassword'];
    $newpassword = $_POST['newPassword'];
    $user_query = "SELECT * FROM `users` WHERE `id` = $user_id";
    $result = mysqli_query($conn,$user_query) or die(mysql_error());
    $count = mysqli_num_rows($result);
    // Check if user exists
    if($count == 0) {
        echo "User with id $user_id does not exist";
        exit();
    }

    $user = mysqli_fetch_assoc($result);
    if($oldpassword == $user['password']) {
        echo "Password matches";
        // Update password
        $update_query = "UPDATE `users` SET `password` = '$newpassword' WHERE `id` = $user_id";
        $result = mysqli_query($conn,$update_query) or die(mysql_error());
        if($result) {
            header('Location: changepassword.php?changes_saved=true');
        } else {
            echo "Error updating password";
        }
    } else {
        header('Location: changepassword.php?no_match=true');
    }
}

$password_updated = false;
if(isset($_GET['changes_saved']) && $_GET['changes_saved'] == 'true') {
    $password_updated = true;
}
if(isset($_GET['no_match']) && $_GET['no_match'] == 'true') {
    $no_match = true;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>change password</title>
    </head>
    <body>
        <div class="container mt-5">
        <h2>Change Password</h2>
        <?php if($password_updated) { ?>
                <div class="alert alert-success" role="alert">
                Password updated successfully
                </div>
        <?php } ?>
        <?php if($no_match) { ?>
                <div class="alert alert-danger" role="alert">
                Old password does not match
                </div>
        <?php } ?>
        <form id="changePasswordForm" method="post" action="changepassword.php">
        <div class="mb-3">
            <label for="oldPassword" class="form-label">Old Password</label>
            <input type="password" class="form-control" id="oldPassword" name="oldPassword" required>
        </div>
        <div class="mb-3">
            <label for="newPassword" class="form-label">New Password</label>
            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
        </div>
        <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
    </div>

    </body>
</html>  