<?php
    /*
        this file gets all the events associated with one specific day
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
    
    $id = (int)$_SESSION["id"];
    $date = htmlentities($_POST["date"]);
    $tag = htmlentities($_POST["tag"]);
    
    if ($tag == "all") {
        $statement = $mysqli->prepare("SELECT * FROM event WHERE (authorid = ? AND date = ?)");
        if (!$statement) {
            echo json_encode(array(
                "success" => false,
                "message" => "Query prep failed"
                ));
                exit;
        }
        $statement->bind_param("is", $id, $date);
        $statement->execute();
        $events = $statement->get_result();
        $statement->close();
    } else {
        $statement = $mysqli->prepare("SELECT * FROM event WHERE (authorid = ? AND date = ? AND tag = ?)");
        if (!$statement) {
            echo json_encode(array(
                "success" => false,
                "message" => "Query prep failed"
                ));
                exit;
        }
        $statement->bind_param("iss", $id, $date, $tag);
        $statement->execute();
        $events = $statement->get_result();
        $statement->close();
    }
    
    $eventarray = array();
    
    while($event = $events->fetch_assoc()) {
        $eventarray[] = $event;
    }
    
    if ($eventarray != null) {
        echo json_encode(array(
            "exist" => true,
            "events" => $eventarray,
            "tag" => $tag
            
        ));
        exit;
    } else {
        echo json_encode(array(
            "exist" => false,
            "events" => $eventarray,
            "query" => $date,
            "tag" => $tag
        ));
        exit;
    }

?>