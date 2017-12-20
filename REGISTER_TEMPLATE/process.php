<?php

    header("Content-Type: application/json");
    session_start();
    require("db_connect.php");
    

    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
    $stmt = $mysqli->prepare("INSERT INTO register_test (username, password) values (?, ?)");
    if (!$stmt) {
        echo json_encode(array(
		    "success" => false,
		    "message" => "Something went wrong during query"
	    ));
        exit;
    } else {
        $stmt->bind_param("ss", $username, $hashed_password);
        $stmt->execute();
        $stmt->close();
        echo json_encode(array(
		    "success" => true,
		    "username" => $username,
		    "message" => "Your event has been added!"
	    ));
        exit;
    }
    
?>