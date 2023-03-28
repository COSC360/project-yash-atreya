<?php
	require('db.php');
	include 'header.php';
	// Check if user is logged in using the session variable
	$credential_are_set = false;
	$user_id = 'no user id';
	$username = 'no username';
	if(isset($_SESSION['username']) && $_SESSION['username'] != 'root' && isset($_SESSION['user_id'])) {
		$username = $_SESSION['username'];
		$user_id = $_SESSION['user_id'];
		$credential_are_set = true;
	}
	// Get all posts
	$query = "SELECT * FROM `posts` ORDER BY `creation_date` DESC";
	$result = mysqli_query($conn,$query) or die(mysql_error());
	$count = mysqli_num_rows($result);
	// Get all posts
	$posts = array();
	while($row = mysqli_fetch_assoc($result)) {
		$posts[] = $row;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .post-url {
      font-size: 0.75rem;
    }

    .post-details {
      font-size: 0.8rem;
    }
  </style>
</head>
<body>
  <div class="container mt-3">
    <div class="list-group">
      <!-- Repeat this for each post in the list -->
	  <?php
	  	if ($count == 0) {
			echo '<center><div>No posts yet</div></center>';
		}
	  	for($i = 0; $i < $count; $i++) {
			echo "<div class='list-group-item'>";
			echo "<div class='d-flex align-items-center'>";
			echo "<span class='me-2'>" . ($i + 1) . ".</span>";
			echo "<div>";
			echo "<div class='d-flex align-items-baseline'>";
			echo "<a href='post.php?id=" . $posts[$i]['id'] . "' class='text-decoration-none text-dark me-2'>" . $posts[$i]['title'] . "</a>";
			if ($posts[$i]['url'] != null || $posts[$i]['url'] != '') {
				echo "<span class='post-url'>(" . $posts[$i]['url'] . ")</span>";
			}
			echo "</div>";
			echo "<div class='post-details'>";
			echo "<span>" . $posts[$i]['upvotes'] . " upvotes</span> | <span>" . $posts[$i]['comments'] . " comments</span> | <span>by " . $posts[$i]['username'] . "</span>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
		}
	  ?>
    </div>
  </div>

  <?php include 'footer.php'; ?>
</body>
</html>
