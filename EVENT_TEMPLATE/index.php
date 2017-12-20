<?php
    session_start();
    require("db_connect.php");
    
?>
<html>
    <head>
    </head>
    <body>
        <p id="result"></p>
        <div id="event">
            <label>Event:</label>
            <input id="eventname" type="text" name="eventname">
            <input id="authorid" type="hidden" name="authorid" value="2"> 
            <button id="newevent">Create</button>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="script.js"></script>
    </body>
</html>