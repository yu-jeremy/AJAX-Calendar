<?php
    /*
        this file gets the current session token
    */

    ini_set("session.cookie_httponly", 1);
    header("Content-type: application/JSON");
    session_start();
    require_once("db_connect.php");
    $previous_ua = @$_SESSION['useragent'];
    $current_ua = $_SERVER['HTTP_USER_AGENT'];
    if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
    	die("Session hijack detected");
    }else{
    	$_SESSION['useragent'] = $current_ua;
    }
    
    
    $token = $_SESSION["token"];
    
    echo json_encode(array(
        "success" => true,
        "token" => $token
    ));
    exit;
?>