<?php
    /*
        this file runs the mysql queries to delete a user and all
        of the events associated with a user
    */

    ini_set("session.cookie_httponly", 1);
    session_start();
    require("db_connect.php");
    header("Content-Type: application/json");
    $previous_ua = @$_SESSION['useragent'];
    $current_ua = $_SERVER['HTTP_USER_AGENT'];
    
    if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
    	die("Session hijack detected");
    }else{
    	$_SESSION['useragent'] = $current_ua;
    }
    
    $id = $_SESSION["id"];
    
    // DELETE USER EVENTS
    $statement = $mysqli->prepare("DELETE FROM event WHERE authorid = ?");
    if (!$statement) {
        echo json_encode(array(
            "success" => false,
            "userDeleted" => false,
            "message" => "Query prep failed"
            ));
            exit;
    } 
    $statement->bind_param("i", $id);
    $statement->execute();
    $statement->close();
    
    // DELETE THE USER
    $statement = $mysqli->prepare("DELETE FROM user WHERE id = ?");
    if (!$statement) {
        echo json_encode(array(
            "success" => false,
            "userDeleted" => false,
            "message" => "Query prep failed"
            ));
            exit;
    } 
    $statement->bind_param("i", $id);
    $statement->execute();
    $statement->close();
    
    session_destroy();
    
    // RETURN SUCCESS
    echo json_encode(array(
        "userDeleted" => true
    ));
    exit;

?>