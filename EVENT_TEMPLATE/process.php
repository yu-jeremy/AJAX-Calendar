<?php

    header("Content-Type: application/json");
    session_start();
    require("db_connect.php");
    

    $eventname = $_POST["eventname"];
    $authorid = $_POST["authorid"];
    
    $stmt = $mysqli->prepare("INSERT INTO test (eventname, authorid) values (?, ?)");
    if (!$stmt) {
        echo json_encode(array(
		    "success" => false,
		    "message" => "Something went wrong during query"
	    ));
        exit;
    } else {
        $stmt->bind_param("si", $eventname, $authorid);
        $stmt->execute();
        $stmt->close();
        echo json_encode(array(
		    "success" => true,
		    "eventname" => $eventname,
		    "message" => "Your event has been added!"
	    ));
        exit;
    }
?>