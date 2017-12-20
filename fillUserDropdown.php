<?php
    /*
        this file gets the username of every user
    */

    ini_set("session.cookie_httponly", 1);
    session_start();
    require("db_connect.php");
    header("Content-type: application/json");
    $previous_ua = @$_SESSION['useragent'];
    $current_ua = $_SERVER['HTTP_USER_AGENT'];
    
    if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
    	die("Session hijack detected");
    }else{
    	$_SESSION['useragent'] = $current_ua;
    }
    
    $id = $_SESSION["id"];
    
    // FILLING DROPDOWN OF OTHER USERS BESIDES LOGGED IN USER
    
    $stmt = $mysqli->prepare("SELECT username FROM user");
    if (!$stmt) {
        echo json_encode(array(
            "success" => false
            ));
            exit;
    }
    
    $stmt->execute();
    $users = $stmt->get_result();
    $stmt->close();
    
    $users_array = array();
    // fill array with users retrieved
    
    while ($user = $users->fetch_assoc()) {
        $users_array[] = $user;
    }
    
    if ($users_array != null) {
        echo json_encode(array(
            "exist" => true,
            "users" => $users_array
            ));
            exit;
    } else {
        echo json_encode(array(
            "exist" => false,
            "users" => $users_array
            ));
            exit;
    }
?>