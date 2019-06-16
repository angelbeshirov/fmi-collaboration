<?php

require "util.php";
require "database_manager.php";

should_redirect_not_logged_in();

    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        handle_get_request();
    } else if($_SERVER["REQUEST_METHOD"] === "DELETE") {
        handle_delete_request();
    } else if($_SERVER["REQUEST_METHOD"] === "POST") {
        handle_post_request();
    }

function handle_get_request() {
    
    $str = explode("/", $_SERVER["REQUEST_URI"]);
    if(sizeof($str) > 2) {
        if($str[2] == "retrieve") {
            get_all_files();
        } else if ($str[2] == "retrieve_shares") {
            get_all_shares();
        } else if($str[2] == "retrieve_my_shares") {
            get_my_shares();
        } else if(strpos($str[2], "retrieve_file_id") === 0 && isset($_GET["filename"])) {
            get_file_id($_GET["filename"]);
        } else if(strpos($str[2], "download") === 0 && isset($_GET["file"])) {
            $file_name = $_GET["file"];
            download_file($file_name);
        } else if(strpos($str[2], "get_share_id") === 0 && isset($_GET["filename"])) {
            $file_name = $_GET["filename"];
            should_start_session();
            $database_manager = new database_manager();
            if(isset($_GET["shared_by"])) {
                $shared_by = $_GET["shared_by"];
                $userID = $database_manager->get_user_by_username($shared_by);
                get_share_id($file_name, $userID[0]["id"], $_SESSION["id"]);
            } else if(isset($_GET["shared_to"])) {
                $shared_to = $_GET["shared_to"];
                $userID = $database_manager->get_user_by_username($shared_to);
                get_share_id($file_name, $_SESSION["id"], $userID[0]["id"]);
            }
        }
    } else {
        echo json_encode(["error_description" => "Невалиден адрес."]);
    }
}

function handle_delete_request() {
    $str = explode("/", $_SERVER["REQUEST_URI"]);
    if(sizeof($str) > 2 && $str[2] == "delete") {
        $file_to_delete = ltrim($str[3], ":");
        delete_file(urldecode($file_to_delete));
    } else if(sizeof($str) > 2 && $str[2] == "delete_share") {
        $share_to_delete = ltrim($str[3], ":");
        delete_share($share_to_delete);
    }
}

function handle_post_request() {
    should_start_session();
    $str = explode("/", $_SERVER["REQUEST_URI"]);
    if(sizeof($str) > 2 && $str[2] == "share") {
        $data = file_get_contents("php://input");
        $share = json_decode($data, true);
        share_file($share);
    }
}

function get_all_files() {
    should_start_session();
    $database_manager = new database_manager();
    if(isset($_SESSION["loggedin"]) && isset($_SESSION["id"]) && $_SESSION["loggedin"]) {
        $result = $database_manager->get_files_for_user($_SESSION["id"]);
        echo json_encode(["files" => $result], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["error_description" => "Изтекла сесия."], JSON_UNESCAPED_UNICODE);
    }
}

function get_all_shares() {
    should_start_session();
    $database_manager = new database_manager();
    if(isset($_SESSION["loggedin"]) && isset($_SESSION["id"]) && $_SESSION["loggedin"]) {
        $shares = $database_manager->get_shares_with_user($_SESSION["id"]);
        echo json_encode(["shares" => $shares], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["error_description" => "Изтекла сесия."], JSON_UNESCAPED_UNICODE);
    }
}

function get_my_shares() {
    should_start_session();
    $database_manager = new database_manager();
    if(isset($_SESSION["loggedin"]) && isset($_SESSION["id"]) && $_SESSION["loggedin"]) {
        $shares = $database_manager->get_shares_by_user($_SESSION["id"]);
        echo json_encode(["shares" => $shares], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["error_description" => "Изтекла сесия."], JSON_UNESCAPED_UNICODE);
    }
}

function get_file_id($filename) {
    should_start_session();
    $database_manager = new database_manager();
    if(isset($_GET["shared_by"])) {
        $user = $database_manager->get_user_by_username($_GET["shared_by"]);
        
        if($user) {
            $file = $database_manager->get_file($user[0]["id"], $filename); // todo add some checks here
            echo json_encode(["file_id" => $file[0]["id"]], JSON_UNESCAPED_UNICODE);
        }
    } else {
        if(isset($_SESSION["loggedin"]) && isset($_SESSION["id"]) && $_SESSION["loggedin"]) {
            
            $file = $database_manager->get_file($_SESSION["id"], $filename);
            echo json_encode(["file_id" => $file[0]["id"]], JSON_UNESCAPED_UNICODE);
        }
    }
}

function download_file($file_name) {
    should_start_session();
    $database_manager = new database_manager();
    if(isset($_SESSION["loggedin"]) && isset($_SESSION["id"]) && $_SESSION["loggedin"]) {
        $result = $database_manager->get_path($_SESSION["id"], $file_name);
        if($result) {
            get_file($result[0]["path"]);
        } else if(isset($_GET["shared_by"])) { // todo change this
            $user = $database_manager->get_user_by_username($_GET["shared_by"]);
			if($user) {
				$userID = $user[0]["id"];
				$share = $database_manager->get_share($userID, $_SESSION["id"], $file_name);
				if($share) {
                    get_file($database_manager->get_path($userID, $file_name)[0]["path"]);
				}
			}
        }
    }
}

function get_file($path) {
    if(file_exists($path)) {
        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"" . basename($path) . "\"");
        header("Expires: 0");
        header("Cache-Control: must-revalidate");
        header("Pragma: public");
        header("Content-Length: " . filesize($path));
        flush();
        readfile($path);
        exit;
    }
}

function delete_file($file_to_delete) {
    should_start_session();
    $database_manager = new database_manager();
    if(isset($_SESSION["loggedin"]) && isset($_SESSION["id"]) && $_SESSION["loggedin"]) {
        $result = $database_manager->get_path($_SESSION["id"], $file_to_delete);
        if($result) {
            $path = $result[0]["path"];
            $real_path = realpath($path);
            if($real_path && is_writable($real_path) && unlink($real_path)) {
                if($database_manager->delete_file_for_user($_SESSION["id"], $file_to_delete)) {
                    echo json_encode(["response" => $file_to_delete . " was deleted successfully"], JSON_UNESCAPED_UNICODE);
                } else {
                    echo json_encode(["error_description" => "Проблем с базата данни!"], JSON_UNESCAPED_UNICODE);
                }
            } else {
                echo json_encode(["error_description" => "Error while delete file " + $file_to_delete], JSON_UNESCAPED_UNICODE);
            }
        }
    }
}

function delete_share($shareID) {
    should_start_session();
    $database_manager = new database_manager();
    if(!$database_manager->delete_share($shareID)) {
        echo json_encode(["error_description" => "Проблем с базата данни!"], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["status" => "Success"], JSON_UNESCAPED_UNICODE);
    }
}

function get_share_id($filename, $shared_by, $shared_to) {
    $database_manager = new database_manager();
    $share = $database_manager->get_share($shared_by, $shared_to, $filename);
    if($share) {
        echo json_encode(["id" => $share[0]["id"]], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["error_description" => "Грешка с базата данни"], JSON_UNESCAPED_UNICODE);
    }
}

function share_file($share) {
    $database_manager = new database_manager();

    $result = $database_manager->get_user_by_email($share["email"]);
    if(!$result) {
        echo json_encode(["error_description" => "Няма потребител с този имейл."], JSON_UNESCAPED_UNICODE);
        return;
    }

    if($result[0]["id"] == $_SESSION["id"]) {
        echo json_encode(["error_description" => "Не може да споделяте файл със себе си."], JSON_UNESCAPED_UNICODE);
        return;
    }

    $is_there_share_already = $database_manager->get_share($_SESSION["id"], $result[0]["id"], $share["filename"]);
    if ($is_there_share_already) {
        echo json_encode(["error_description" => "Файлът вече е споделен с този имейл."], JSON_UNESCAPED_UNICODE);
    } else {
        $database_manager->add_share($_SESSION["id"], $result[0]["id"], $share["filename"]);
        echo json_encode(["email" => $share["email"]], JSON_UNESCAPED_UNICODE);
    }
}
?>