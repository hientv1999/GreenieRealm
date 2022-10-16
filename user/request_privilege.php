<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
include "../template/check_alive.php";
// Include config file
require_once "../classes/Table.php";
$db_handle = new DBController();
$table = new Table();

//check if this is the first username

$username = $_SESSION["username"];
if(isset($_GET[$username.'-request'])) {
    $sql = "INSERT INTO request (username) VALUE (?)";
    $db_handle->insert($sql, "s", array($username));
    header("Refresh:0; url=management.php");
}
if(isset($_GET[$username.'-remove'])) {
    $sql = "DELETE FROM request WHERE username = ?";
    $db_handle->insert($sql, "s", array($username));
    header("Refresh:0; url=management.php");
}

?>