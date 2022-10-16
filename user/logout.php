<?php
// Initialize the session
session_start();
require_once "../classes/Util.php";
require_once "../classes/Auth.php";
$util = new Util();
$auth = new Auth();
$username = $_SESSION["username"];
$userToken = $auth->getTokenByUsername($username);
if (! empty($userToken[0]["id"])) {
    $auth->removeExpired($userToken[0]["id"]);
}
$util->clearAuthCookie();
// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session.
session_destroy();

// Redirect to login page
$redirect_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . "/index.php";
header("Location: " .$redirect_link);
exit;
?>