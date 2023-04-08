<!DOCTYPE html>
<html>
    <body>
        <h2 class="mt-5 mb-4 text-center"><?php echo $user['username'],'\'s' ?> Profile</h2>
        <div class="container">
            <div class="card">
                <div class="row g-0">
                    <!-- Profile image column -->
                
                    
                        <?php 
                        if($image != null) {
                            echo '<div class="col-md-4 d-flex align-items-center justify-content-center">';
                            echo '<img src="' . ($image != null ? 'data:' . $content_type . ';base64,' . base64_encode($image) : 'path/to/default-image.png') . '" class="img-fluid rounded mx-auto" alt="Profile Image" style="max-width: 200px; max-height: 200px;">'; 
                            echo '</div>';
                        }
                        ?>
                    <!-- User details column -->
                    <div class="col-md-8">
                        <div class="card-body">
                            <!-- <h5 class="card-title"><?php echo $user['username']; ?></h5> -->
                            <p class="card-text"><strong>Email:</strong> <?php echo $user['email']; ?></p>
                            <p class="card-text"><strong>Creation Date:</strong> <?php echo $user['creation_date']; ?></p>
                            <div class="mb-3">
                                <?php echo "<a href='user.php?id=" . $id . "&posts=true'>View Posts</a>" ?>
                                <br>
                                <?php echo "<a href='user.php?id=" . $id . "&comments=true'>View Comments</a>" ?>
                                <br>
                                <?php 
                                  if (isset($_SESSION['username']) && $_SESSION['username'] == $username) {
                                      echo "<a href='editprofile.php'>Edit Profile</a> ";
                                      echo "<br>";
                                      
                                      echo "<a href='changepassword.php'>Change Password</a> ";
                                      echo "<br>";
                                      echo "<br>";
                                      if($user['isAdmin'] == 1) {
                                        echo "You are an admin: ";
                                        echo "<br>";
                                        echo "<a href='admin.php'>Manage Users</a>";
                                        echo "<br>";
                                        echo "<a href='manage_posts.php'>Manage Posts</a>";
                                        echo "<br>";
                                        echo "<br>";
                                      }
                                      echo '<a href="logout.php" class="btn btn-danger">Logout</a>';
                                      
                                  }
                                ?>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
