<?php
require('db.php');
// Get query string
$id = get_user_id();
if($id == -1) {
    // TODO: Display error message: User does not exist and exit
} else {
    // Get user from database
    $query = "SELECT * FROM `users` WHERE `id` = $id";
    $result = mysqli_query($conn,$query) or die(mysql_error());
    $count = mysqli_num_rows($result);
    // Check if user exists
    if($count == 0) {
        // TODO: Display error message: User does not exist and exit
    }
    // Get user
    $user = mysqli_fetch_assoc($result);
    // if(isset($_SESSION['username']) && $_SESSION['username'] == $user['username']) {
    //     // logout button
    //     echo "<a href='logout.php'>Logout</a>";
    // }
}

function get_user_id() {
    if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
        return $_GET['id'];
    } else {
        return -1;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>user</title>
        <?php include 'header.php'; ?>
    </head>
    <body>
    <div class="container">
    <div class="row">
      <div class="col-12">
        <h1 class="mt-5 mb-4">User Profile</h1>
        <div class="table-responsive">
          <table class="table table-bordered">
            <tbody>
              <tr>
                <th>username</th>
                <?php echo "<td>" . $user['username'] . "</td>"; ?>
              </tr>
              <tr>
                <th>email</th>
                <?php echo "<td>" . $user['email'] . "</td>"; ?>
              </tr>
              <tr>
                <th>creation date</th>
                <?php echo "<td>" . $user['creation_date'] . "</td>"; ?>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- PHP code to display logout button if it's the profile of the current user -->
        <?php 
            if(isset($_SESSION['username']) && $_SESSION['username'] == $user['username']) {
                // logout button
                echo '<a href="logout.php" class="btn btn-danger">logout</a>';
            }
        ?>

      </div>
    </div>
    </div>
</body>
</html>