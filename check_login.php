<?php
    /*
        this file checks if a user is currently logged in or not
    */

    ini_set("session.cookie_httponly", 1);
    header("Content-Type: application/json");
    session_start();
    $previous_ua = @$_SESSION['useragent'];
    $current_ua = $_SERVER['HTTP_USER_AGENT'];
    
    if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
    	die("Session hijack detected");
    }else{
    	$_SESSION['useragent'] = $current_ua;
    }
    
    if ((isset($_SESSION['id'])) && (trim($_SESSION['id'] != ""))) {
        echo json_encode(array(
            "isLoggedIn" => true,
            "username" => $_SESSION['username'],
            "id" => $_SESSION["id"]
            ));
            exit;
    } else {
        unset($_SESSION['token']);
        echo json_encode(array(
            "isLoggedIn" => false,
            "username" => null
            ));
            exit;
    }
?>