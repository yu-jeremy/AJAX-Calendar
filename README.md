# AJAX-Calendar

This project uses AJAX requests to asynchronously update the state of a calendar. Built with the AMP stack, JS scripts and originally run on an AWS EC2 instance. Tested using Apache and the Cloud9 IDE.

**Project Partner Credits: Zhi Shen Yong**

### Project Walkthrough

This project's front-end is built with HTML and CSS while its back-end component is built using MySQL and PHP. 
JavaScript is used to handle asynchronous AJAX calls. Users stay on [index.php](index.php) and there are no 
redirects or refreshes to this page or any other page. This is accomplished through use of AJAX. Other 
PHP files deal with processing data and inserting/updating/removing MySQL entries. For example, 
[getEvents.php](getEvents.php) gets all the eventsthat are scheduled for a particular day and that 
are also associated with the particular user that is logged in. 

JS files deal mostly with AJAX, which is what allows us to make the website
dynamic without any refreshes or redirects. Every PHP file or PHP function is 
associated with a JS file or JS function. While obvious JS functions like 
logging in or registering are stored in external JS files such as [login.js](login.js)
and [register.js](register.js), respectively, other JS functions that deal with 
how the calendar functions are stored in [calendar.js](calendar.js). Note that MOST
of the complex AJAX logic is stored in [calendar.js](calendar.js)!

We configured two tables in our MySQL database; one for users and one for events; a foreign key exists
between the "authorid" column in the events table and the "id" column of the users table (which is its primary key). 
