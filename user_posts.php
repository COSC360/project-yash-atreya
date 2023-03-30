<!DOCTYPE html>
<html>
    <body>
    <?php 
        echo "<h2 class='mt-5 mb-4'>" . $user['username'] . "'s Posts</h2>"; 
        if($posts_count == 0) {
            echo "<center><div>No posts yet</div></center>";
            exit();
        }
        echo "<div class='list-group'>";
        for($i = 0; $i < $posts_count; $i++) {
			echo "<div class='list-group-item'>";
			echo "<div class='d-flex align-items-center'>";
			echo "<span class='me-2'>" . ($i + 1) . ".</span>";
			echo "<div>";
			echo "<div class='d-flex align-items-baseline'>";
			echo "<a href='post.php?id=" . $posts[$i]['id'] . "' class='text-decoration-none text-dark me-2'>" . $posts[$i]['title'] . "</a>";
			if ($posts[$i]['url'] != null || $posts[$i]['url'] != '') {
				echo "<span class='post-url'>(<a href='" . $posts[$i]['url'] . "'>" . $posts[$i]['url']. "</a>)</span>";
			}
			echo "</div>";
			echo "<div class='post-details'>";
			echo "<span>" . $posts[$i]['upvotes'] . " upvotes</span> | <span>" . $posts[$i]['comments'] . " comments</span> | <span>by " . $posts[$i]['username'] . "</span>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
		}
        echo "</div>";
    ?>
    </body>
</html>    