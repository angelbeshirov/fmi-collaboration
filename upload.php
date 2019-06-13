<?php
	require "util.php";
	require "db.php";

	shouldRedirectNotLoggedIn();

	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		if(isset($_FILES["filesToUpload"])) {
			should_start_session();
			
			$errors = [];
			$path = "uploads/" . $_SESSION["id"] . "/";
			$extensions = ["doc", "docx", "txt"];

			if (!is_dir($path)) {
				mkdir($path);
			}

			$all_files = count($_FILES["filesToUpload"]["tmp_name"]);
			for ($i = 0; $i < $all_files; $i++) {
				$localErrors = [];
				$file_name = $_FILES["filesToUpload"]["name"][$i];
				$file_tmp = $_FILES["filesToUpload"]["tmp_name"][$i];
				$file_type = $_FILES["filesToUpload"]["type"][$i];
				$file_size = $_FILES["filesToUpload"]["size"][$i];

				$tmp = explode(".", $_FILES["filesToUpload"]["name"][$i]);
				$file_extension = strtolower(end($tmp));

				$file = $path . $file_name;

				if (!in_array($file_extension, $extensions)) {
					$localErrors[] = "Extension not allowed: " . $file_name;
					$errors[] = "Extension not allowed: " . $file_name;
				}

				if ($file_size > 2097152) {
					$localErrors[] = "File size exceeds limit: " . $file_name;
					$errors[] = "File size exceeds limit: " . $file_name;
				}

				if (file_exists($file)) {
					$localErrors[] = "File " . $file_name . " already exists.";
					$errors[] = "File " . $file_name . " already exists.";
				}

				if (empty($localErrors)) {
					move_uploaded_file($file_tmp, $file);
					save_to_database($file, $file_name, $file_size, $file_extension);
				}
			}

        	if ($errors) {
				echo json_encode(["error_description" => $errors], JSON_UNESCAPED_UNICODE);
			} else {
				echo json_encode(["status" => "Success"], JSON_UNESCAPED_UNICODE);
			}
		}
	}

function get_file_extension_ID($file_extension) {
	$SELECT_EXT = "SELECT id FROM types WHERE extension = ?;";
	$result = selectQuery($SELECT_EXT, array($file_extension));
	return $result[0]["id"];
}

function save_to_database($file, $file_name, $file_size, $file_extension) {
	$ADD_FILE = "INSERT INTO files (created_by, path, name, size, uploaded_on, last_changed, type) VALUES (?, ?, ?, ?, ?, ?, ?);";
	date_default_timezone_set("Europe/Sofia");
	should_start_session();
	if(isset($_SESSION["id"])) {
		$user_id = $_SESSION["id"];
		$file_extension_id = get_file_extension_ID($file_extension);
		$date = date("Y-m-d H:i:s", time());
		if(!executeQuery($ADD_FILE, array($user_id, $file, $file_name, $file_size, $date, $date, $file_extension_id))) {
			echo json_encode(["error_description" => "Грешка с базата данни."], JSON_UNESCAPED_UNICODE);
		}
	}
}
?>