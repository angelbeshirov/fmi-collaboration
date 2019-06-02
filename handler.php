<?php
    function handleRegistration($user) {
        $ADD_USER = "INSERT INTO accounts (email, username, password) VALUES (?, ?, ?);";
        $errors = validateRegistration($user);
        if (empty($errors)) {
            $hash = password_hash($user["password"], PASSWORD_DEFAULT);
            if(executeQuery($ADD_USER, array($user["email"], $user["username"], $hash))) {
                echo json_encode(["register" => $user], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(["error_description" => "Грешка с базата данни."], JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode($errors, JSON_UNESCAPED_UNICODE);
        }
    }
    
    function handleLogin($user) {
        if(!isset($_SESSION)) { 
            $GET_USER = "SELECT id, email, username, password FROM accounts WHERE email=?;";
            $result = selectQuery($GET_USER, array($user["email"]));
            shouldStartSession();
            if($result && password_verify($user["password"], $result[0]["password"])) {
                $_SESSION["id"] = $result[0]["id"];
                $_SESSION['loggedin'] = true;
                setcookie("displayInfo", $result[0]["username"], time() + 7200, "/", NULL, NULL, FALSE);
                echo json_encode(["login" => $result[0]], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(["error_description" => "Невалиден имейл или парола."], JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(["loggedin" => true], JSON_UNESCAPED_UNICODE);
        }
    }

    function handleIsLoggedIn() {
        shouldStartSession();
        if(isset($_SESSION["id"])) {
            echo json_encode(["loggedin" => true], JSON_UNESCAPED_UNICODE);
         } else {
            echo json_encode(["loggedin" => false], JSON_UNESCAPED_UNICODE);
         }
    }
    
    function handleLogout() {
        shouldStartSession();
    
        if(isset($_SESSION["id"])) {
            if(isset($_COOKIE["displayInfo"])){
                setcookie("displayInfo", "", time() - 7200, "/", NULL, NULL, FALSE);
            }
            $params = session_get_cookie_params();
            setcookie(session_name(), "", 0, $params["path"], $params["domain"], $params["secure"], isset($params["httponly"]));
            session_destroy();

            echo json_encode(["loggedin" => false], JSON_UNESCAPED_UNICODE);
        }
    }

    function handleRetrieve() {
        shouldStartSession();
        if(isset($_SESSION["role"]) && $_SESSION["role"] == "Admin") {
            $GET_USERS = "SELECT email, name, last_name, password, role_name FROM users INNER JOIN role ON (users.user_role = role.id);";
            $result = selectQuery($GET_USERS, array());
            if($result) {
                echo json_encode(["users" => $result], JSON_UNESCAPED_UNICODE);
            } else echo json_encode(["error_description" => "Грешка с базата данни"], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["error_description" => "Нямате достъп до тази операция."], JSON_UNESCAPED_UNICODE);
        }
    }

    function shouldStartSession() {
        if(!isset($_SESSION)) { 
            session_start(); 
        }
    }
?>