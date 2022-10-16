<?php
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["username"])){
    require_once "../classes/Util.php";
    $util = new Util();
    $util->clearAuthCookie();
    // Unset all of the session variables
    $_SESSION = array();
    
    // Destroy the session.
    session_destroy();

    // Redirect to login page
    $redirect_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . "/index.php";
    header("Location: " .$redirect_link);
    exit;
}

?>