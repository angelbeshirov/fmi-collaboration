<?php
require "handler.php";
require "validator.php";
require "db.php";

    $str = explode("/", $_SERVER["REQUEST_URI"]);
    if(sizeof($str) > 2) {
        switch ($str[2]) {
            case "register":
                $registerJson = file_get_contents("php://input");
                $newUser = json_decode($registerJson, true);
                handleRegistration($newUser);
                break;
            case "login":
                $loginJSON = file_get_contents("php://input");
                $user = json_decode($loginJSON, true);
                handleLogin($user);
                break;
            case "isLoggedIn":
                handleIsLoggedIn();
                break;
            case "logout":
                handleLogout();
                break;
        }
    } else {
        echo json_encode(["error_description" => "Невалиден адрес."]);
    }
?>