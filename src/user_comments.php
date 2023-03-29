<!DOCTYPE html>
<html>
    <body>
    <?php 
        echo "<h2 class='mt-5 mb-4'>" . $user['username'] . "'s comments</h2>"; 
        if($comments_count == 0) {
            echo "<center><div>No comments yet</div></center>";
            exit();
        }
        echo "<div class='list-group'>";
        for($i = 0; $i < $comments_count; $i++) {
			echo "<div class='list-group-item'>";
			echo "<div class='d-flex align-items-center'>";
			echo "<span class='me-2'>" . ($i + 1) . ".</span>";
			echo "<div>";
			echo "<div class='d-flex align-items-baseline'>";
			echo "<span" . $comments[$i]['id'] . "' class='text-decoration-none text-dark me-2'>" . $comments[$i]['text'] . "</span>";
			if ($comments[$i]['url'] != null || $comments[$i]['url'] != '') {
				echo "<span class='post-url'>(<a href='" . $comments[$i]['url'] . "'>" . $comments[$i]['url']. "</a>)</span>";
			}
			echo "</div>";
			echo "<div class='post-details'>";
			echo "<span>" . $comments[$i]['upvotes'] . " upvotes</span> | <span>" . $comments[$i]['comments'] . " comments</span> | <span>by <a href='user.php?id=".$comments[$i]['user_id']."'>" . $comments[$i]['username'] . "</a></span>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
		}
        echo "</div>";
    ?>
    </body>
</html> 