<?php
    /* this file deals with editing an event, and is only available to
    the person who authored the event */

    ini_set("session.cookie_httponly", 1);
    header("Content-Type: application/JSON");
    session_start();
    require("db_connect.php");
    $previous_ua = @$_SESSION['useragent'];
    $current_ua = $_SERVER['HTTP_USER_AGENT'];
    
    if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
    	die("Session hijack detected");
    }else{
    	$_SESSION['useragent'] = $current_ua;
    }
    
    
    $id = (int) $_POST["id"];
    $eventname = htmlentities($_POST["eventname"]);
    $description = htmlentities($_POST["description"]);
    $date = htmlentities($_POST["date"]);
    $token = $_POST["token"];
    $tag = $_POST["tag"];
    

    /*
    unset($_SESSION["error_array"]);
    $error_array = array();
    $_SESSION["error_array"] = $error_array;
    */
 
    if ((!isset($_POST["id"])) || (trim($_POST["id"] == "")) ||
    (!isset($_POST["eventname"])) || (trim($_POST["eventname"])) ||
    (!isset($_POST["description"])) || (trim($_POST["description"])) ||
    (!isset($_POST["time"])) || (trim($_POST["time"])) || 
    (!isset($_POST["tag"])) || (trim($_POST["tag"]))) {
    
        if ($token != $_SESSION["token"]) {
            echo json_encode(array(
                "success" => false,
                "eventDeleted" => false,
                "message" => "Token failed."
                ));
                exit;
        }
    
    /*
        if(!preg_match("^[0-9]+$", $id)) {
            $_SESSION["error_array"][] = "id_error";
        }
        
        if(!preg_match("/^[a-zA-Z0-9 ]*$/", $eventname)) {
            $_SESSION["error_array"][] = "event_name_error";
        }
        
        if(!preg_match("/^[a-zA-Z0-9 ]*$/", $description)) {
            $_SESSION["error_array"][] = "description_error";
        }
        
        */
        
        $stmt = $mysqli->prepare("UPDATE event SET eventname = ? WHERE id = ? AND authorid = ?");
        if (!$stmt) {
            echo json_encode(array(
    		    "success" => false,
    		    ));
    		    exit;
        }
        $stmt->bind_param("sii", $eventname, $id, $_SESSION["id"]);
        $stmt->execute();
        $stmt->close();
    
        $stmt = $mysqli->prepare("UPDATE event SET tag = ? WHERE id = ? AND authorid = ?");
        if (!$stmt) {
            echo json_encode(array(
    		    "success" => false,
    		    ));
    		    exit;
        }
        $stmt->bind_param("sii", $tag, $id, $_SESSION["id"]);
        $stmt->execute();
        $stmt->close();
    
        
        $stmt = $mysqli->prepare("UPDATE event SET description = ? WHERE id = ? AND authorid = ?");
        if (!$stmt) {
            echo json_encode(array(
    		    "success" => false,
    		    ));
    		    exit;
        } 
        $stmt->bind_param("sii", $description, $id, $_SESSION["id"]);
        $stmt->execute();
        $stmt->close();
        
        $stmt = $mysqli->prepare("UPDATE event SET date = ? WHERE id = ? AND authorid = ?");
        if (!$stmt) {
            echo json_encode(array(
    		    "success" => false,
    		    ));
    		    exit;
        } 
        $stmt->bind_param("sii", $date, $id, $_SESSION["id"]);
        $stmt->execute();
        $stmt->close();
    
        echo json_encode(array(
            "success" => true,
    	    "eventname" => $eventname,
    	    "description" => $description,
    	    "date" => $ddate,
    	    "authorid" => $authorid
    	    ));
    	    exit;
    } else {
        echo json_encode(array(
            "success" => false,
            ));
            exit;
    }
?>