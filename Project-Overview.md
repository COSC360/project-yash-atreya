# Project/System Overview

The project uses the LAMP stack (Linux, Apache, MySQL, PHP) to provide a web-based interface for users to interact with the system. It is a clone of [HackerNews](https://news.ycombinator.com/), a popular news aggregator site.
The project is written in PHP and uses MySQL as the database. The project is deployed on the [cosc360.ok.ubc.ca](https://cosc360.ok.ubc.ca/yash0102) server.

## Project Structure

1. `index.php` - The main page of the website. It displays posts sorted by recency.
2. `post.php` - Displays a single post and its comments.
3. `login.php` - Allows users to login to the website.
4. `register.php` - Allows users to register for the website.
5. `logout.php` - Clears the session and logs the user out.
6. `submit.php` - Allows users to submit a new post.
7. `user.php` - Displays a user's profile.
8. `upvote.php` - Handles requests to upvote/downvote a post.
9. `db.php` - Contains the database connection information.
10. `checkdisabled.php` - Checks if a user is disabled.
11. `admin.php` - Allows admins to disable users.
13. `manage_posts.php` - Allows admins to edit/delete posts.
14. `header.php` - Contains the header of the website.
15. `footer.php` - Contains the footer of the website.
16. `changepassword.php` - Allows users to change their password.
17. `edit_post.php` - Allows admin to edit posts.
19. `enable_user.php` - Handles requests to enable a user.
20. `disable_user.php` - Handles requests to disable a user.
21. `user_posts.php` - Displays a user's posts.
22. `user_comments.php` - Displays a user's comments.
23. `make_admin.php` - Handles requests to make a user an admin.
24. `remove_admin.php` - Handles requests to remove admin privileges from a user.
25. `ddl.sql` - Contains the DDL for the database.
26. `scripts/validate-login` - Client side validation for login form.
27. `scripts/validate-register` - Client side validation for register form.

## Database Structure

The database contains 4 tables:

1. `users` - Contains information about the users of the website. It contains a `tinyint(1)` field `isAdmin` that is set to 1 if the user is an admin. It also contains a `tinyint(1)` field `isDisabled` that is set to 1 if the user is disabled.

    a. PRIMARY KEY: `id`
2. `posts` - Contains information about the posts and comments on the website. Comments are stored as posts with a `parent_id` that references the post they are a comment of. They also have a `tinyint(1)` field `isComment` that is set to 1 if the post is a comment. Moreover, comments also have a `in_reply_to_id` that references the post they are replying to, which could be post or even a comment.
    
    a. PRIMARY KEY: `id`

    b. FOREIGN KEY: `user_id` (references `users.id`)
3. `upvotes` - Contains the upvotes for each post.

    a. PRIMARY KEY: `id`
    
    b. FOREIGN KEY: `user_id` (references `users.id`)

    c. FOREIGN KEY: `post_id` (references `posts.id`)
4. `userImages` - Contains the profile image of each user.

    a. PRIMARY KEY: `id`
    
    b. FOREIGN KEY: `user_id` (references `users.id`)
