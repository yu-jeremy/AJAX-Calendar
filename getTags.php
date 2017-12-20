<?php
    /*
        this file gets all the distinct tags associated with the 
        events created by the user who is running this file. this means 
        that duplicate tags are only shown once
    */

    ini_set("session.cookie_httponly", 1);
    header("Content-Type: application/json");   
    session_start();
    require("db_connect.php");
    $previous_ua = @$_SESSION['useragent'];
    $current_ua = $_SERVER['HTTP_USER_AGENT'];
    
    if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
    	die("Session hijack detected");
    }else{
    	$_SESSION['useragent'] = $current_ua;
    }
    
    $id = (int) $_SESSION["id"];
    
    $stmt = $mysqli->prepare("SELECT DISTINCT tag FROM event WHERE authorid = ?");
    if (!$stmt) {
        echo json_encode(array(
		    "success" => false,
	    ));
        exit;
    } 
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $tagg = $stmt->get_result();
    $stmt->close();
    
    $tagsarray = array();
    while($tag = $tagg->fetch_assoc()) {
        $tagsarray[] = $tag;
    }
    //$tagsarray = array_unique($tagsarray);
    
    if ($tagsarray != null) {
        echo json_encode(array(
            "success" => true,
            "tagg" => $tagsarray,
        ));
        exit;
    } else {
        echo json_encode(array(
            "success" => false,
            "tagg" => $tagsarray,
        ));
        exit;
    }
    
?>