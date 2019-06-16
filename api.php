<?php
require "handler.php";
require "validator.php";
require "util.php";
require "database_manager.php";

    $str = explode("/", $_SERVER["REQUEST_URI"]);
    if(sizeof($str) > 2) {
        switch ($str[2]) {
            case "register":
                $register_json = file_get_contents("php://input");
                $new_user = json_decode($register_json, true);
                handle_registration($new_user);
                break;
            case "login":
                $login_json = file_get_contents("php://input");
                $user = json_decode($login_json, true);
                handle_login($user);
                break;
            case "isLoggedIn":
                handle_is_logged_in();
                break;
            case "logout":
                handle_logout();
                break;
        }
    } else {
        echo json_encode(["error_description" => "Невалиден адрес."]);
    }
?>