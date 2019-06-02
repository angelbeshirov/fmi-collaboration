<?php
function redirect($url, $statusCode = 303)
{
   header("Location: " . $url, true, $statusCode);
   die();
}

function shouldRedirectLoggedIn() {
   if(!isset($_SESSION)) { 
      session_start(); 
   }
   if(isset($_SESSION["id"])) {
      redirect("index.php");
   }
}

function shouldRedirectNotLoggedIn() {
   if(!isset($_SESSION)) { 
      session_start(); 
   }
   if(!isset($_SESSION["id"])) {
      redirect("index.php");
   }
}
?>