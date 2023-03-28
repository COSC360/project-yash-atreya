<?php 
require('db.php');
// require('checkdisabled.php');
// Start the session
session_start();
// Check if user is logged in using the session variable
$credential_are_set = false;
$user_id = 'no user id';
$username = 'no username';
if(isset($_SESSION['username']) && $_SESSION['username'] != 'root' && isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
    $credential_are_set = true;
}
?>
<!-- <!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="A short description." />
    <meta name="keywords" content="put, keywords, here" />
    <meta name="robots" content="noindex" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>ubc news</title>

     Latest compiled and minified CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous"> -->

    <!-- Optional theme -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"> -->

    <!-- <link rel="stylesheet" href="style.css"> -->
<!-- </head> -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>ubc news</title>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">
        ubc news
      </a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="index.php">home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="submit.php">submit</a>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <!-- TODO: Implement Search -->
          <!-- <li class="nav-item">
            <form class="d-flex">
              <input class="form-control me-2" type="search" placeholder="search" aria-label="Search">
              <button class="btn btn-outline-success" type="submit">search</button>
            </form>
          </li> -->
          <li class="nav-item">
            <?php 
                if($credential_are_set) {
                    echo '<a class="nav-link" href="user.php?id=' . $user_id . '">' . $username . '</a>';
                } else {
                    echo '<a class="nav-link" href="login.php">login</a>';
                }
            ?>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>