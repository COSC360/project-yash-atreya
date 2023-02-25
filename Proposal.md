# Project Proposal

## Team Members

- Yash Atreya, 86003902

## Project Description

- This project is a simple online forum like [Reddit](https://reddit.com) or [HackerNews](https://news.ycombinator.com/). 
- It's first page will diretly show the list of posts which can be sorted by time, upvotes, tags, and trending. This page can be viewed by anyone without registering. 
- However, in order to post, comment or upvote one needs to create an account or login.
- Users will be create, edit and delete their posts and comments. They can upvote a post or a comment.
- Users can create posts with the following details: title, url and text.
- Moreover, users can add flair or tags to their posts like reddit.
- Users can create an account or login using their ```.ubc.ca``` email address. 
- They can set their username and bio after creating an account.

## Deliverables

1. Home Page - ```/```

    This is the main page of the project which will show all the submitted posts.
    
    Posts can be sorted by time, upvotes, tags, and trending.

    Unregistred users can only view the posts.

    This will also contain a search bar to search for posts.

2. Login Page - ```/login```

    This page will allow users to login using their ```.ubc.ca``` email address.

    It will have an email and password field.

3. Register Page - ```/register```

    This page will allow users to create an account using their ```.ubc.ca``` email address.

    It will have an email and password field.

4. Submit Post Page - ```/submit```

    This page will allow logged in users to create a post.

    It will have a title, url and text field and tag selection for the post.

5. Read Post Page - ```/post/:id```

    This page will show the details of a post.

    It will show the title, url, text, tags, upvotes, comments and comment form to submit a comment for logged in users.

    Unregistred users can only view the post.

6. User Profile Page - ```/user/:id```

    This page will show the details of a user.

    It will show the email, profile picture,  username, bio, posts, comments and upvotes.

7. Admin Dashboard Page - ```/admin```

    This page can only be accessed by users with the admin role. 
    
    Admins can add give admin role to other users.

    This dashboard can be used to track metrics such as number of posts, comments, users, etc.

    Moderate issues and help with forgotten passwords of users.

