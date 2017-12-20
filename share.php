<?php
    /* 
        this function essentially duplicates a row, except swaps out 
        the author id for the id of the person that a share event is 
        intended to go towards.
    */

    ini_set("session.cookie_httponly", 1);
    require("db_connect.php");
    session_start();
    header("Content-type: application/json");
    
    $previous_ua = @$_SESSION['useragent'];
    $current_ua = $_SERVER['HTTP_USER_AGENT'];
    if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
    	die("Session hijack detected");
    }else{
    	$_SESSION['useragent'] = $current_ua;
    }
    
     $id = (int) $_SESSION["id"]; // session id
     $event_id = (int) $_POST["event_id"];
     $name = $_POST["name"];
     
     $stmt = $mysqli->prepare("SELECT authorid FROM event WHERE id = ?");
     $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $stmt->bind_result($gottenID);
    $stmt->fetch();
    $stmt->close();
    
    if ($gottenID != $id) {
        echo json_encode(array(
                success => false,
                message => "impostor",
                ));
            exit;
    }
     
     $stmt = $mysqli->prepare("SELECT id FROM user WHERE username = ?");
     $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->bind_result($jeremyAuthorID);
        $stmt->fetch();
        $stmt->close();
    
    
    $stmt = $mysqli->prepare("SELECT * FROM event WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $theEvents = $stmt->get_result();
    $stmt->close();
    
    while($theEvent = $theEvents->fetch_assoc()) {
        $stmt = $mysqli->prepare("INSERT INTO event (eventname, description, date, time, authorid, tag) values (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            echo json_encode(array(
                success => false,
                message => "fail",
                ));
            exit;
        } else {
            $stmt->bind_param("ssssis", $theEvent["eventname"], $theEvent["description"], $theEvent["date"], $theEvent["time"], $jeremyAuthorID, $theEvent["tag"]);
            $stmt->execute();
            $stmt->close();
            echo json_encode(array(
                success => true,
                message => "success",
                ));
            exit;
        }
    }
?>