<?php
require 'checkdisabled.php';
require('db.php');

if(isset($_SESSION['username']) && $_SESSION['username'] != 'root' && isset($_SESSION['user_id'])) {
    header("Location: index.php");
}

if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Validate the input
    if(empty($username) || empty($email) || empty($password)) {
        $msg = "Please fill all the fields";
        // echo $msg;
    }
    // Validate the username
    if(!validateUsername($username)) {
        $msg = "Username must be between 5 and 15 characters and can only contain letters and numbers";
        // echo $msg;
    }
    // Validate the password
    if(!validatePassword($password)) {
        $msg = "Password must be between 5 and 15 characters and can only contain letters and numbers";
        // echo $msg;
    }
    // Check if the username is already taken
    $query = "SELECT * FROM `users` WHERE username='$username'";
    $result = mysqli_query($conn,$query);
    if(mysqli_num_rows($result) > 0) {
        $msg = "Username already taken";
        // echo $msg;
    }
    // Check if the email is already taken
    $query = "SELECT * FROM `users` WHERE email='$email'";
    $result = mysqli_query($conn,$query);
    if(mysqli_num_rows($result) > 0) {
        $msg = "Email already taken, you should try logging in";
        // echo $msg;
    }
    // Insert the user into the database
    $query = "INSERT INTO `users` (username, email, password) VALUES ('$username', '$email', '$password')";
    $result = mysqli_query($conn,$query);
    // Get the id of the user
    $msg = "Registration successful";
    $query = "SELECT * FROM `users` WHERE username='$username'";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_assoc($result);
    $user_id = $row['id'];

    // Upload the profile image
    if(isset($_FILES['profile_image'])) {
        // Check file size of longblob sql
        if($_FILES['profile_image']['size'] > 1000000) {
            echo "File too large";
            http_response_code(400);
            exit();
        }
        // Check file type
        $allowedFileTypes = array("image/jpeg", "image/png", "image/gif", "image/jpg");
        $content_type = mime_content_type($_FILES['profile_image']['tmp_name']);
        if(!in_array($content_type, $allowedFileTypes)) {
            $msg = "File type not allowed";
            echo $msg;
            http_response_code(400);
        }

        $targetFile = "uploads/" . basename($_FILES['profile_image']['name']);
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile);
        $image = file_get_contents($targetFile);
        // Insert the image into the database
        $query = "INSERT INTO `userImages` (`user_id`, `image`, `content_type`, `username`) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn); 
        mysqli_stmt_prepare($stmt, $query);
        $null = NULL;
        mysqli_stmt_bind_param($stmt, "ibss", $user_id, $null, $content_type, $username);
        mysqli_stmt_send_long_data($stmt, 1, $image);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "Image uploaded successfully";
    }
    // session_start();
    // Set the session variables
    echo "Username: $username, User ID: $user_id";
    $_SESSION['username'] = $username;
    $_SESSION['user_id'] = $user_id;
    echo "Session username: " . $_SESSION['username'];
    echo "Session user id: " . $_SESSION['user_id'];
    // header("Location: index.php");
    exit();
    
    mysqli_close($conn);
}

function validateUsername($username) {
    if(!preg_match('/^[a-zA-Z0-9]{5,15}$/', $username)) {
        return false;
    }
    return true;
}


function validatePassword($password) {
    if(!preg_match('/^[a-zA-Z0-9]{5,15}$/', $password)) {
        return false;
    }
    return true;
}
// TODO: Check if session already exists
?>

<html>
    <head>
        <title>Register</title>
        <?php include('header.php'); ?>
        
    </head>
    <body>
    <script type="text/javascript" src="scripts/validate-register.js"></script>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <h1 class="text-center my-4">register</h1>
                <form action="register.php" id="register-form" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="username">username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="profile_image">profile image</label>
                        <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*" required>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">submit</button>
                </form>
                <p class="text-center mt-2">
                    already have an account? <a href="login.php" class="text-muted">login here</a>
                </p>
            </div>
        </div>
    </div>
    </body>
</html>
