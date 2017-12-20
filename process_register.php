<?php
    /* register for a new account here */

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
    
    $date = date("Y-m-d H:i:s");
    $firstname = htmlentities($_POST["firstname"]);
    $lastname = htmlentities($_POST["lastname"]);
    $username = htmlentities($_POST["reg_user"]);
    $password = htmlentities($_POST["reg_pwd"]);
    
    /*
    unset($_SESSION["error_array"]);
    $error_array = array();
    $_SESSION["error_array"] = $error_array;
    */
    
     $error_array = array();
    
    if ((!isset($_POST["reg_user"])) || (trim($_POST["reg_user"] == "")) ||
    (!isset($_POST["reg_pwd"])) || (trim($_POST["reg_pwd"] == "")) ||
    (!isset($_POST["firstname"])) || (trim($_POST["firstname"] == "")) ||
    (!isset($_POST["lastname"])) || (trim($_POST["lastname"] == ""))) {
     
     //$error_array[] = "register_blank";
     echo json_encode(array(
         "success" => false,
         "message" => "Inputs were left blank"
         ));
         exit;
    } else {
        
        
        if(!preg_match("/^[a-zA-Z ]*$/", $firstname)) {
            $error_array[] = "firstname_error";
            
        }
        
        if (!preg_match("/^[a-zA-z ]*$/", $lastname)) {
            $error_array[] = "lastname_error";
            
        }
        
        if (strlen($password)>30) {
            $error_array[] = "reg_password_error";
           
        }
        
        if (strlen($username)>30) {
            $error_array[] = "reg_username_error";
        }
        
        if (count($error_array) == 0) {
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $mysqli->prepare("SELECT first_name FROM user WHERE username=?");
            if (!$stmt) {
                echo json_encode(array(
                    "success" => false,
                    "message" => "Something went wrong during query"
                    ));
                    exit;
            } else {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->store_result();
                
                if ($stmt->num_rows == 0) {
                    $stmt->close();
                    
                    $stmt = $mysqli->prepare("INSERT INTO user (first_name, last_name, username, password, datejoined) values (?, ?, ?, ?, ?)");
                    if (!$stmt) {
                        echo json_encode(array(
                            "success" => false,
                            "message" => "something went wrong during query"
                            ));
                            exit;
                    } else {
                        $stmt->bind_param("sssss", $firstname, $lastname, $username, $hashed_password, $date);
                        $stmt->execute();
                        $stmt->close();
                        echo json_encode(array(
                            "success" => true,
                            "message" => "Successfully registered"
                            ));
                            exit;
                    }
                } else {
                    $stmt-> close();
                    $error_array[] = "user_exists";
                    echo json_encode(array(
                        "success" => false,
                        "message" => "User already exists"
                        ));
                        exit;
                }
            }
            
        } else {
            echo json_encode(array(
                "success" => false,
                "message" => "Invalid username/password/first name/last name"
                ));
                exit;
        }
    }
?>
