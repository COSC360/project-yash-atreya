<!DOCTYPE html>
<html>
    <body>
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
        <div>
          <?php echo "<a href='user.php?id=" . $id . "&posts=true'>View Posts</a>" ?>
          <br>
          <?php echo "<a href='user.php?id=" . $id . "&comments=true'>View Comments</a>" ?>
          <br>
          <!-- Edit Profile -->
          <?php 
            if(isset($_SESSION['username']) && $_SESSION['username'] == $user['username']) {
                // edit profile button
                echo "<a href='editprofile.php'>Edit Profile</a>";
                echo "<br>";
                echo "<a href='changepassword.php'>Change Password</a>";
            }
          ?>
        </div>
        <br>
        <!-- PHP code to display logout button if it's the profile of the current user -->
        <?php 
            if(isset($_SESSION['username']) && $_SESSION['username'] == $user['username']) {
                // logout button
                echo '<a href="logout.php" class="btn btn-danger">logout</a>';
            }
        ?>
      </div>
    </body>
<html>