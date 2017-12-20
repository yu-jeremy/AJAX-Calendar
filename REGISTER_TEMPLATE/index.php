<?php
    session_start();
    require("db_connect.php");
    
?>
<html>
    <head>
    </head>
    <body>
        <p id="result"></p>
        <div id="register_form">
            <label>Register:</label>
            <input id="username" type="text" name="username">
            <input id="password" type="password" name="password"> 
            <button id="newuser">Add User</button>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js "></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js "></script>
        <script src="script.js"></script>
    </body>
</html>