<?php
function redirect($url, $statusCode = 303)
{
   header("Location: " . $url, true, $statusCode);
   die();
}

function shouldRedirectLoggedIn() {
   session_start(); 
   if(isset($_SESSION["id"])) {
      redirect("index.php");
   }
}

function shouldRedirectNotLoggedIn() {
   session_start(); 
   if(!isset($_SESSION["id"])) {
      redirect("index.php");
   }
}

function should_start_session() {
	if(!isset($_SESSION)) { 
		session_start(); 
	}
}
?>