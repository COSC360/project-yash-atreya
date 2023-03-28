<?php
require 'db.php';
// Admin variables
$adminLoggedIn = false;
$adminUsername = null;

// Check if the logged in user is an admin

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
    }
} else {
    $adminLoggedIn = false;
    $adminUsername = null;
}

// Get query string
$username_query = isset($_GET['username']) ? $_GET['username'] : null;
$email_query = isset($_GET['email']) ? $_GET['email'] : null;

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if($username_query && $email_query) {
        $query = "SELECT * FROM `users` WHERE `username` LIKE '$username_query%' OR `email` LIKE '$email_query%'";
    } else if($username_query) {
        $query = "SELECT * FROM `users` WHERE `username` LIKE '$username_query%'";
    } else if($email_query) {
        $query = "SELECT * FROM `users` WHERE `email` LIKE '$email_query%'";
    } else {
        $query = "SELECT * FROM `users`";
    }
    getResults($conn, $query);    
}
function getResults($conn, $query) {
    // global $conn;
    global $result; 
    $result = mysqli_query($conn,$query) or die(mysql_error());
    global $num_rows;
    $num_rows = mysqli_num_rows($result);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>admin</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <?php require 'header.php'; ?>
</head>
<body>
<div class="container mt-5">
    <!-- Search form -->
    <form id="search-form" class="mb-4">
      <div class="input-group">
        <input type="text" class="form-control" id="search" placeholder="search user by username or email">
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </form>

    <!-- Search results table -->
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Username</th>
          <th scope="col">Email</th>
          <th scope="col">Status</th>
          <th scope="col">Action</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody id="results-tbody">
        <!-- Example row with Enable/Disable button -->
        <?php 
          for($i = 0; $i < $num_rows; $i++) {
            echo "<tr>";
            $row = mysqli_fetch_assoc($result);
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td id='status'>" . ($row['isDisabled'] == 1 ? "Disabled" : "Enabled") . "</td>";
            if($row['isDisabled'] == 1) {
              echo "<td><button id='enable' class='btn btn-success'>Enable</button></td>";
            } else {
              echo "<td><button id='disable' class='btn btn-danger'>Disable</button></td>";
            }
            if($row['isAdmin'] == 1) {
              echo "<td><button id='remove-admin' class='btn btn-warning'>Remove as admin</button></td>";
            } else {
              echo "<td><button id='make-admin' class='btn btn-primary'>Make Admin</button></td>";
            }
            echo "</tr>";
          }
        ?>
        <!-- <tr>
          <td>username</td>
          <td>email@example.com</td>
          <td id="status-1">Enabled</td>
          <td><button id="disable" class="btn btn-danger">Disable</button></td>
          <td><button id=make-admin" class="btn btn-primary">Make Admin</button></td>
        </tr> -->
      </tbody>
    </table>
  </div>
</body>