<?php
	require('db.php');
	// CHeck if user is disabled
	require('checkdisabled.php');
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

	if(isset($_GET['search']) && $_GET['search'] != '') {
		$search = $_GET['search'];
		$query = "SELECT * FROM `posts` WHERE `isComment` = 0 AND (`title` LIKE '%$search%' OR `text` LIKE '%$search%')  ORDER BY `creation_date` DESC";
		$result = mysqli_query($conn,$query) or die(mysql_error());
		$count = mysqli_num_rows($result);
		// Get all posts
		$posts = array();
		while($row = mysqli_fetch_assoc($result)) {
			$posts[] = $row;
		}
	} else {
		// Get all posts
		$query = "SELECT * FROM `posts` WHERE `isComment` = 0 ORDER BY `creation_date` DESC";
		$result = mysqli_query($conn,$query) or die(mysql_error());
		$count = mysqli_num_rows($result);
		// Get all posts
		$posts = array();
		while($row = mysqli_fetch_assoc($result)) {
			$posts[] = $row;
		}
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
	.upvote-btn {
      border: none;
      background: none;
      color: gray;
      font-size: 1rem;
      padding: 0;
      margin: 0;
    }
  </style>
</head>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('search-form');
    form.addEventListener('submit', function(e) {
      // e.preventDefault();
      console.log("Setting action to: " + 'index.php?search=' + document.getElementById('search').value);
      // form.setAttribute('action', 'admin.php?search=' + document.getElementById('search').value);
      window.location.href = 'index.php?search=' + document.getElementById('search').value;
      e.preventDefault();
    });

	// Upvote button
	var upvoteBtns = document.querySelectorAll('.upvote-btn');

	// Loop through all upvote buttons
	for(let i = 0; i < upvoteBtns.length; i++) {
		// Add event listener to each button
		upvoteBtns[i].addEventListener('click', function(e) {
			// Get post id
			var post_id = e.target.getAttribute('data-post-id');
			// Get user id
			var user_id = e.target.getAttribute('data-user-id');
			// Get username
			var username = e.target.getAttribute('data-username');
			var is_upvoted = e.target.getAttribute('is-upvoted');
			console.log('post_id: ' + post_id);
			console.log('user_id: ' + user_id);
			console.log('username: ' + username);
			console.log('is_upvoted: ' + is_upvoted);
			if(is_upvoted === 'true') {
				// Send request to upvote.php
				var xhr = new XMLHttpRequest();
				xhr.open('POST', 'upvote.php', true);
				xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
				xhr.onload = function() {
					// Check if request was successful
					console.log("Response: ", JSON.stringify(this.response, null, 4));
					console.log("ResponseText: " + this.responseText);
					console.log("ResponseObj: ", this.responseObject);

					
					if(this.responseText === 'Upvote decreased') {
						// Update upvote for the particulat post
						const query = 'span[class="upvote-count-' + post_id + '"]';
						let upvoteCountSpan = document.querySelector('span[class="upvote-count-' + post_id + '"]');
						let count = upvoteCountSpan.getAttribute('data-upvote-count');
						// Decrement count
						count--;
						// Update count
						upvoteCountSpan.setAttribute('data-upvote-count', count);
						// Update text
						upvoteCountSpan.innerHTML = count + ' upvotes';
						// Set is-upvoted to false
						e.target.setAttribute('is-upvoted', 'false');
						// Change color of upvote button to blue
						e.target.style.color = 'gray';
					}

				}
				xhr.send('post_id=' + post_id + '&user_id=' + user_id + '&username=' + username + '&increase=false');
			} else if (is_upvoted === 'false') {
				// Send request to upvote.php
				var xhr = new XMLHttpRequest();
				xhr.open('POST', 'upvote.php', true);
				xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
				xhr.onload = function() {
					// Check if request was successful
					console.log("Response: ", JSON.stringify(this.response, null, 4));
					console.log("ResponseText: " + this.responseText);
					console.log("ResponseObj: ", this.responseObject);

					
					if(this.responseText === 'Already upvoted' || this.responseText === 'Upvote increased') {
						// Update upvote for the particulat post
						const query = 'span[class="upvote-count-' + post_id + '"]';
						let upvoteCountSpan = document.querySelector('span[class="upvote-count-' + post_id + '"]');
						let count = upvoteCountSpan.getAttribute('data-upvote-count');
						// Increment count
						count++;
						// Update count
						upvoteCountSpan.setAttribute('data-upvote-count', count);
						// Update text
						upvoteCountSpan.innerHTML = count + ' upvotes';
						// Set is-upvoted to true
						e.target.setAttribute('is-upvoted', 'true');
						// Change color of upvote button to blue
						e.target.style.color = 'blue';
					}

				}
				xhr.send('post_id=' + post_id + '&user_id=' + user_id + '&username=' + username + '&increase=true');
				// Receive response from upvote.php
			}
		});
	}
});
</script>
<body>
  <div class="container mt-3">
  <form id="search-form" class="mb-4" method="get" action="index.php?search=">
      <div class="input-group">
        <input type="text" class="form-control" id="search" placeholder="search posts">
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </form>
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
			// Upvote button on if user is logged in
			if ($credential_are_set) {
				echo "<button class='upvote-btn me-2' is-upvoted='false' data-post-id='".$posts[$i]['id']."'data-user-id='".$user_id."' data-username='".$username."'>&#9650;</button>";
			}
			echo "<div>";
			echo "<div class='d-flex align-items-baseline'>";
			echo "<a href='post.php?id=" . $posts[$i]['id'] . "' class='text-decoration-none text-dark me-2'>" . $posts[$i]['title'] . "</a>";
			if ($posts[$i]['url'] != null || $posts[$i]['url'] != '') {
				echo "<span class='post-url'>(<a href='" . $posts[$i]['url'] . "'>" . $posts[$i]['url']. "</a>)</span>";
			}
			echo "</div>";
			echo "<div class='post-details'>";
			echo "<span class='upvote-count-".$posts[$i]['id']."' data-upvote-count='".$posts[$i]['upvotes']."' >".$posts[$i]['upvotes']." upvotes</span> | <span>" . $posts[$i]['comments'] . " comments</span> | by<a href='user.php?id=" . $posts[$i]['user_id'] . "'> " . $posts[$i]['username'] . "</a>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
		}
	  ?>
    </div>
  </div>
</body>
</html>
