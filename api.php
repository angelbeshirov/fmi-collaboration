<?php
require "handler.php";
require "validator.php";
require "util.php";
require "database_manager.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    handle_get_request();
} else if($_SERVER["REQUEST_METHOD"] === "POST") {
    handle_post_request();
}

function handle_get_request() {
    $str = explode("/", $_SERVER["REQUEST_URI"]);
    if(sizeof($str) > 2) {
        switch ($str[2]) {
            case "is_logged_in":
                handle_is_logged_in();
                break;
            case "logout":
                handle_logout();
                break;
        }
    }
}

function handle_post_request() {
    $str = explode("/", $_SERVER["REQUEST_URI"]);
    if(sizeof($str) > 2) {
        switch ($str[2]) {
            case "register":
                $json = file_get_contents("php://input");
                $user = json_decode($json, true);
                handle_registration($user);
                break;
            case "login":
                $json = file_get_contents("php://input");
                $user = json_decode($json, true);
                handle_login($user);
                break;
        }
    }
}
?>