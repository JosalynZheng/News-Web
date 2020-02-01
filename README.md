# CSE330
466838

466949

The url for our news website:

http://ec2-54-144-117-199.compute-1.amazonaws.com/~bo/module3/mainpage.php

User & password that can login:

jessy : 12345


Creative Features:

1.Search

Everyone can search storys either by title or username(Fuzzy Search)

2.Edit user 

After user login, they can change their username to the name that did not store in database under the "Own" link.

3.Tags

For each news story, we add a tag attribute to it, such like social part, health part and so on. Then we can find news by tags.

4.Add likes and liked page

This feature only can be used by logined users, you can select which news they like and click the like button, then you can find the story in your liked page. Also, after you liked it, you can choose to dislike it.

5.Own page

This page will list all stories that you wrote, allowing users to edit their stories and username.

6.upload image

When logined users click addstory, they can choose to upload a image for this story, but it not the required part, users do not have to load a image for each news.


---

Grade: **61/75**  

Comments:  
##### Relational database is configured with correct data types and foreign keys:
-2: did not include results of `SHOW CREATE TABLE`; only included results of `DESCRIBE TABLE`. Can see the data types, but cannot tell the exact key reference structure

##### Stories can be posted:
-3: cannot add new story (`addStory.php` does not render anything)

##### A link can be associated with each story using a separate database field: 
-3: `link` not found in the DB fields

##### Site follows the FIEO philosophy: 
-3: vulnerable to XSS attack (entered `<script>alert("malicious");</script>` into the comments box and Javascript executed

##### All pages pass the W3C validator:
-2: mainpage.php has multiple Errors from the HTML validator (`Element p not allowed as child of element ul in this context`

##### Site is intuitive to use and navigate:
-1: can edit posts only in "ownPage" tab, and editing window does not show what the title and content were before
