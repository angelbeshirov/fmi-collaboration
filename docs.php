<?php

require "util.php";
require "db.php";

shouldRedirectNotLoggedIn();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $str = explode("/", $_SERVER["REQUEST_URI"]);
        if(sizeof($str) > 2) {
            if($str[2] == "retrieve") {
                get_all_files();
            } else if(strpos($str[2], "download") === 0 && isset($_GET["file"])) {
                $file_name = $_GET["file"];
                download_file($file_name);
            }
        } else {
            echo json_encode(["error_description" => "Невалиден адрес."]);
        }
    } else if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $str = explode("/", $_SERVER["REQUEST_URI"]);
        if(sizeof($str) > 2 && $str[2] == "delete") {
            
            $file_to_delete = ltrim($str[3], ':');
            delete_file(urldecode($file_to_delete));
        }
    }

function get_all_files() {
    should_start_session();
    if(isset($_SESSION["loggedin"]) && isset($_SESSION["id"]) && $_SESSION["loggedin"]) {
        $GET_FILES = "SELECT files.name, files.size, files.uploaded_on, files.last_changed, types.type_name 
        FROM files INNER JOIN types ON files.type = types.id 
        WHERE created_by = ?;";

        $result = selectQuery($GET_FILES, array($_SESSION["id"]));
        echo json_encode(["files" => $result], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["error_description" => "Изтекла сесия."], JSON_UNESCAPED_UNICODE);
    }
}

function download_file($fileName) {
    should_start_session();
    if(isset($_SESSION["loggedin"]) && isset($_SESSION["id"]) && $_SESSION["loggedin"]) {
        $GET_PATH = "SELECT files.path FROM files WHERE created_by = ? AND name = ?";
        $result = selectQuery($GET_PATH, array($_SESSION["id"], $fileName));
        
        $path = $result[0]["path"];
        if(file_exists($path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($path).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($path));
            flush();
            readfile($path);
            exit;
        }
    }
}

function delete_file($file_to_delete) {
    should_start_session();
    if(isset($_SESSION["loggedin"]) && isset($_SESSION["id"]) && $_SESSION["loggedin"]) {
        $GET_PATH = "SELECT files.path FROM files WHERE created_by = ? AND name = ?";
        $result = selectQuery($GET_PATH, array($_SESSION["id"], $file_to_delete));
        
        $path = $result[0]["path"];

        $real_path = realpath($path);
        if($real_path && is_writable($real_path) && unlink($real_path)) {
            $DELETE_FILE = "DELETE FROM files WHERE created_by = ? AND name = ?";
            if(executeQuery($DELETE_FILE, array($_SESSION["id"], $file_to_delete))) {
                echo json_encode(["response" => $file_to_delete . " was deleted successfully"], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(["error_description" => "Проблем с базата данни!"], JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(["error_description" => "Error while delete file " + $file_to_delete], JSON_UNESCAPED_UNICODE);
        }
    }
}
?>