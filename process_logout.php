<?php
    /* logout */

    ini_set("session.cookie_httponly", 1);
    
    session_start();
    $previous_ua = @$_SESSION['useragent'];
    $current_ua = $_SERVER['HTTP_USER_AGENT'];
    
    if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
    	die("Session hijack detected");
    }else{
    	$_SESSION['useragent'] = $current_ua;
    }
    
    unset($_SESSION["token"]);
    unset($_SESSION['id']);
    unset($_SESSION['username']);
    $_SESSION = [];
    session_destroy();
    
    exit;
?>