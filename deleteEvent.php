<?php
    /* 
        this file runs the mysql queries to delete an event 
        only if the person who is running the function is the 
        author of the event.
    */

    ini_set("session.cookie_httponly", 1);
    require("db_connect.php");
    session_start();
    header("Content-Type: application/json");
    $previous_ua = @$_SESSION['useragent'];
    $current_ua = $_SERVER['HTTP_USER_AGENT'];
    
    if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
    	die("Session hijack detected");
    }else{
    	$_SESSION['useragent'] = $current_ua;
    }
    
    $id = (int) $_POST["id"]; // this is the event id we are trying to delete
    $token = $_POST["token"];
    
    if ($token != $_SESSION["token"]) {
        echo json_encode(array(
            "success" => false,
            "eventDeleted" => false,
            "message" => "Token failed."
            ));
            exit;
    }
    
    $statement = $mysqli->prepare("SELECT authorid FROM event WHERE id = ?");
    if (!$statement) {
        echo json_encode(array(
            "success" => false,
            "eventDeleted" => false,
            "message" => "Query prep failed"
            ));
            exit;
    } 
    $statement->bind_param("i", $id);
    $statement->execute();
    $statement->bind_result($author_id);
    $statement->fetch();
        
    if ((int)$author_id == (int)$_SESSION["id"]) {
        $statement->close();
        $statement = $mysqli->prepare("DELETE FROM event WHERE id = ?");
        if (!$statement) {
            echo json_encode(array(
                "success" => false,
                "eventDeleted" => false,
                "message" => "Query prep failed"
                ));
                exit;
        } 
        $statement->bind_param("i", $id);
        $statement->execute();
        $statement->close();
        echo json_encode(array(
            "eventDeleted" => true
        ));
        exit;
    } else {
        $statement->close();
        echo json_encode(array(
            "success" => false,
            "eventDeleted" => false,
            "message" => "You cannot delete another user's event."
            ));
            exit;
    }
 
    
?>