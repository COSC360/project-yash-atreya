# Client Side Experience

Yash Atreya, 86003902

## Layout
---

## Page Organization and Navigation
---
As described in the proposal before, the site will feature seven main/top level pages, complemented by subpages to improve the user experience. 

The main pages are:

1. Home Page

Route - ```/```

File - ```index.html```

This is the landing page of the website.

It has a navbar that contains has two types of links.
The ```new```, ```trending```, ```ask``` and ```show``` which are query links to sort the posts on the main page.

The other links are link to pages such as ```submit```.
More links such as ```login``` or ```profile``` will be added depending on whether the user is logged in or not.

The main sections of the page is populated by posts that link to the relevant post page like ```post/:id```.

2. Post Page

Route - ```/post/:id```

File - ```post.html```

This page is the page that displays a single post with its comments.

3. Submit Page

Route - ```/submit```

File - ```submit.html```

This page is the page that allows the user to submit a new post if he/she is logged in, otherwise it redirects to the ```login``` page.

4. Login Page

Route - ```/login```

File - ```login.html```

This page is the page that allows the user to login to the site. If the user is new to the site, he/she can go to the ```register``` page.

5. Register Page

Route - ```/register```

File - ```register.html```

This page is the page that allows the user to register to the site. If the user is already registered, he/she can go to the ```login``` page.

6. User Page

Route - ```/user?id=username```

File - ```user.html```

This page is the page that displays the profile of a user. It also displays the links to the user's submissions, comments and upvoted posts. (Sub-pages)

The URL for subspages will be of the form:
    
    ```/user?id=username&tab=submissions```
    
    ```/user?id=username&tab=comments```
    
    ```/user?id=username&tab=upvoted```

7. Admin Page

Route - ```/admin```

File - ```admin.html```

This page is the page that allows the admin to manage the site. It will be accessible only to the admin.

## Logic Process
---
How does the user engage with the site?

1. The user lands on the home page and can see the posts that are trending or new or ask or show without logging in.

2. The user can click on the ```login``` link in the navbar to login to the site.

3. If the user is new to the site, he/she can click on the ```register```  link on the login page to register to the site.

4. If the user is logged in, he/she can click on the ```submit``` link in the navbar to submit a new post.

5. To view a post, a user can click on the post title and will be taken to the post page containing the post and its comments.

6. If logged in, the user can upvote or comment on a post from the home page or the post page.
Comments can also upvoted.

7. The user can click the username displayed on a post or comment to view the user's profile and view details such as bio, submissions, comments, upvoted posts, and karma.
```Karma``` is the score that is assigned to a user based on the number of upvotes he/she has received.

8. The user can also visit their own profile and update all their details if required.

## Design and Styling
---
