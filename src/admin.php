<?php
require 'db.php';
// Admin variables
$adminLoggedIn = false;
$adminUsername = null;
include 'header.php';

// Disable Request

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
    } else {
        $adminLoggedIn = false;
        $adminUsername = null;
        echo "<center><div>You are not an admin</div></center>";
        exit();
    }
} else {
    echo "You are not logged in";
    $adminLoggedIn = false;
    $adminUsername = null;
}

// Get query string
$search_query = isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : null;

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if($search_query) {
      $query = "SELECT * FROM `users` WHERE username LIKE '$search_query%' OR email LIKE '$search_query%'";
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
</head>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('search-form');
    form.addEventListener('submit', function(e) {
      // e.preventDefault();
      console.log("Setting action to: " + 'admin.php?search=' + document.getElementById('search').value);
      // form.setAttribute('action', 'admin.php?search=' + document.getElementById('search').value);
      window.location.href = 'admin.php?search=' + document.getElementById('search').value;
      e.preventDefault();
    });
    
    // Add event listeners to all disable buttons
    var disabledButtons = document.querySelectorAll('#disable');
    for(let i = 0; i < disabledButtons.length; i++) {
      disabledButtons[i].addEventListener('click', function(e) {
        console.log("Disable button clicked")

        // Get the user id of the user to disable
        var disable_user_id = e.target.getAttribute('user_id');
        console.log("User id to disable: " + disable_user_id);

        // Send a post request to disable the user
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'disableuser.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('disable_user_id=' + disable_user_id);
        xhr.onload = function() {
          console.log(this.responseText);
          if(this.status == 200) {
            console.log(this.responseText);
            window.location.href = 'admin.php';
          }
        }
      });
    }

    // Add event listeners to all enable buttons
    var enabledButtons = document.querySelectorAll('#enable');
    for(let i = 0; i < enabledButtons.length; i++) {
      enabledButtons[i].addEventListener('click', function(e) {
        console.log("Enable button clicked");

        // Get the user id of the user to enable
        var enable_user_id = e.target.getAttribute('user_id');
        console.log("User id to enable: " + enable_user_id);

        // Send a post request to enable the user
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'enableuser.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('enable_user_id=' + enable_user_id);
        xhr.onload = function() {
          console.log(this.responseText);
          if(this.status == 200) {
            console.log(this.responseText);
            window.location.href = 'admin.php';
          }
        }
      });
    }

    // Add event listeners to all make-admin buttons
    var makeAdminButtons = document.querySelectorAll('#make-admin');
    for(let i = 0; i < makeAdminButtons.length; i++) {
      makeAdminButtons[i].addEventListener('click', function(e) {
        console.log("Make admin button clicked");

        // Get the user id of the user to make admin
        var make_admin_user_id = e.target.getAttribute('user_id');
        console.log("User id to make admin: " + make_admin_user_id);

        // Send a post request to make the user admin
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'makeadmin.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('make_admin_user_id=' + make_admin_user_id);
        xhr.onload = function() {
          console.log(this.responseText);
          if(this.status == 200) {
            console.log(this.responseText);
            window.location.href = 'admin.php';
          }
        }
      });
    }

    // Add event listeners to all remove-admin buttons
    var removeAdminButtons = document.querySelectorAll('#remove-admin');
    for(let i = 0; i < removeAdminButtons.length; i++) {
      removeAdminButtons[i].addEventListener('click', function(e) {
        console.log("Remove admin button clicked");

        // Get the user id of the user to remove admin
        var remove_admin_user_id = e.target.getAttribute('user_id');
        console.log("User id to remove admin: " + remove_admin_user_id);

        // Send a post request to remove the user admin
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'removeadmin.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('remove_admin_user_id=' + remove_admin_user_id);
        xhr.onload = function() {
          console.log(this.responseText);
          if(this.status == 200) {
            console.log(this.responseText);
            window.location.href = 'admin.php';
          }
        }
      });
    }

});
</script>
<body>
<div class="container mt-5">
    <!-- Search form -->
    <form id="search-form" class="mb-4" method="get" action="admin.php?search=">
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
            echo "<td><a href='user.php?id=" . $row['id'] . "'>" . $row['username'] . "</a></td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td id='status'>" . ($row['isDisabled'] == 1 ? "Disabled" : "Enabled") . "</td>";
            if($row['isDisabled'] == 1 && $row['id'] != $_SESSION['user_id']) {
              echo "<td><button id='enable' user_id='" . $row['id'] . "' class='btn btn-success'>Enable</button></td>";
            } else if($row['isDisabled'] == 0 && $row['id'] != $_SESSION['user_id']) {
              echo "<td><button id='disable' user_id='" . $row['id'] . "' class='btn btn-danger'>Disable</button>";
            }
            if($row['isAdmin'] == 1 && $row['id'] != $_SESSION['user_id']) {
              echo "<td><button id='remove-admin' user_id='" . $row['id'] . "' class='btn btn-warning'>Remove as admin</button></td>";
            } else if($row['isAdmin'] == 0 && $row['id'] != $_SESSION['user_id']) {
              echo "<td><button id='make-admin' user_id='" . $row['id'] . "' class='btn btn-primary'>Make Admin</button></td>";
            }
            echo "</tr>";
          }
        ?>
      </tbody>
    </table>
  </div>
</body>