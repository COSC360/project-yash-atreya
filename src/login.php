<?php
include 'header.php';
require('db.php');

if(isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Check against db
    $query = "SELECT * FROM `users` WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn,$query) or die(mysql_error());
    $count = mysqli_num_rows($result);
    // Get the id of the user
    $row = mysqli_fetch_assoc($result);
    $user_id = $row['id'];
    if($count == 1) {
        // Start the session
        session_start(); 
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user_id;
        header("Location: index.php");
    } else {
        $msg = "Invalid username or password";
        echo $msg;
    }
}

if(isset($_SESSION['username']) && isset($_SESSION['user_id'])) {
    header("Location: index.php");
} else {
    ?>
<html>

<head>
    <title>Login</title>
    <meta name="robots" content="noindex" />

</head>

<body>

    <div class="container">
        <form class="form-signin" method="POST">
            <?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div>
            <?php } ?>
            <h2 class="form-signin-heading">Please Login</h2>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1">@</span>
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password"
                required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
            <a class="btn btn-lg btn-primary btn-block" href="register.php">Register</a>
        </form>
    </div>

</body>

</html>
<?php } ?>