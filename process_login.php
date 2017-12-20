<?php
    /* a file that processes login for the user using
    the hashed password */

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
    
    $username = htmlentities($_POST["login_user"]);
    $password = $_POST["login_pwd"];
    
    if (isset($_POST["login_user"]) && isset($_POST["login_pwd"]) &&
    (trim($_POST["login_user"] != "")) && (trim($_POST["login_pwd"] != ""))) {
        
        // prepare and execute a statement to fetch the entry in MySQL
        $statement = $mysqli->prepare("SELECT COUNT(*), password, id FROM user WHERE username = ?");
        if (!$statement) {
            echo json_encode(array(
                "success" => false,
                "message" => "Query prep failed"
                ));
                exit;
        }
        $statement->bind_param("s", $username);
        $statement->execute();
        $statement->bind_result($count, $hashed_password_from_database, $id);
        $statement->fetch();
        
        // if there is one user such that the passwords match, log them in
        if ($count == 1 && password_verify($password, $hashed_password_from_database)) {
            $statement->close();
            $_SESSION["id"] = $id;
            $_SESSION["username"] = $username;
            
            unset($_SESSION["token"]);
            // initiate a csrf token
            $_SESSION["token"] = bin2hex(openssl_random_pseudo_bytes(32));
                
            // pass the token as part of the JSON
            echo json_encode(array(
                "success" => true,
                "token" => $_SESSION["token"],
                "username" => $_SESSION["username"],
                "message" => "Login successful"
                ));
                exit;
        } else { 
            $statement->close();
            echo json_encode(array(
                "success" => false,
                "message" => "Username is incorrect or is not registered"
                ));
                exit;
        }
     } else {
         echo json_encode(array(
             "success" => false,
             "message" => "Some login fields were blank."
             ));
             exit;
     }
?>