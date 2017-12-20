<?php

    /*
         this file deals with inserting an event into the database
    */

    header("Content-Type: application/JSON");
    ini_set("session.cookie_httponly", 1);
    session_start();
    require("db_connect.php");
    $previous_ua = @$_SESSION['useragent'];
    $current_ua = $_SERVER['HTTP_USER_AGENT'];
    
    if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
    	die("Session hijack detected");
    }else{
    	$_SESSION['useragent'] = $current_ua;
    }
    
    $eventname = htmlentities($_POST["eventname"]);
    $ddate = htmlentities($_POST["ddate"]);
    $description = htmlentities($_POST["description"]);
    $authorid = (int)$_SESSION["id"];
    $time = htmlentities($_POST["time"]);
    $tag = htmlentities($_POST["tag"]);
    $token = $_POST["token"];
    
    if ((!isset($_POST["eventname"])) || (trim($_POST["eventname"] == "")) 
    || (!isset($_POST["ddate"])) || (trim($_POST["ddate"] == ""))
    || (!isset($_POST["description"])) || (trim($_POST["description"] == ""))
    || (!isset($_POST["time"])) || (trim($_POST["time"] == ""))) {
     echo json_encode(array(
            "success" => false,
            "message" => "One of the fields was not set"
            ));
            exit;
        
    } else { // if one of the fields is not set
        
            
            
        if ($token != $_SESSION["token"]) {
            echo json_encode(array(
                "success" => false,
                "eventDeleted" => false,
                "message" => "Token failed."
                ));
                exit;
        } else {
    
            // make sure that multiple spaces are not counted
            
            if ($tag == "" || $tag == "null") {
                $tag = "all";
            }
        
            $stmt = $mysqli->prepare("INSERT INTO event (eventname, description, date, time, authorid, tag) values (?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                echo json_encode(array(
        		    "success" => false,
        	    ));
                exit;
            } else {
                $stmt->bind_param("ssssis", $eventname, $description, $ddate, $time, $authorid, $tag);
                $stmt->execute();
                $stmt->close();
                echo json_encode(array(
                    "success" => true,
                    "eventname" => $eventname,
                    "description" => $description,
                    "date" => $ddate,
                    "time" => $time,
                    "tag" => $tag,
                    "authorid" => $authorid
                    ));
                    exit;
            }
        } 
    }
?>