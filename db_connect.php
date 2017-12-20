<?php

    // welcome to db_connect.php
    // please include this file at the beginning of every page that will use queries
    // you can do that with 
    //      require_once("db_connect.php");
    
    // to access the c9 mysql terminal follow these steps
    // 1. go to bash
    // 2. type mysql-ctl start
    // 3. type mysql-ctl cli
    
    // now you can use normal mysql commands like "show databases;"
    
    /* here are the table information
    
    user table
        id
        first_name
        last_name
        username
        password
        datejoined
        
    event table
        id
        eventname
        description
        date
        time
        authorid
    
    Other:
    
    test table
        id
        eventname
        authorid
    
    register_test table
        id
        username
        password
    */
    
    // below will connect you to the c9 database
    /*
    $servername = getenv('IP');
    $username = getenv('C9_USER');
    $password = "";
    $database = "module5";
    $dbport = 3306;
    $mysqli = new mysqli($servername, $username, $password, $database, $dbport);
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    */
    
    
    
    // this will NOT work for connecting to aws
    // but it is an easy fix that we can do later
    
    
    $mysqli = new mysqli('localhost', 'wustl_inst', 'wustl_pass', 'module5');
    
    
    if ($mysqli->connect_error) {
        die("Connection failed: ". $mysqli->connect_error);
    }
    
    
    
    

?>