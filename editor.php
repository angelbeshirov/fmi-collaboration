<?php
require "util.php";
require "db.php";
require "doc2txt.class.php";

shouldRedirectNotLoggedIn();

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["open"])) {
	$file_to_open = $_GET["open"];
	should_start_session();
    if(isset($_SESSION["loggedin"]) && isset($_SESSION["id"]) && $_SESSION["loggedin"]) {
        $GET_PATH = "SELECT files.path FROM files WHERE created_by = ? AND name = ?";
        $result = selectQuery($GET_PATH, array($_SESSION["id"], $file_to_open));
        
        $path = $result[0]["path"];
        if(file_exists($path)) {
			// $docObj = new Doc2Txt("$path");
			// $txt = $docObj->convertToText();
			
			$txt = nl2br(file_get_contents($path));
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0>
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<link rel="stylesheet" type="text/css" media="screen" href="css/editor.css">
	<script type="text/javascript" src="js\tinymce/tinymce.min.js"></script>
  	<script>tinymce.init({selector:'textarea'});</script>
</head>

<body id="gradient">
		<textarea id="content" name="content"> 
			<?php echo $txt; ?>
		</textarea>
      <!-- <script type="text/javascript">
        CKEDITOR.replace('content');
      </script> -->
</body>
</html>