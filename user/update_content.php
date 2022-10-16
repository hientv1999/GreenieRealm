<?php
// Initialize the session
session_start();
// Include config file
require_once "../classes/Table.php";
$db_handle = new DBController();
$table = new Table();

//check current privilege
if ($_SESSION["privilege"] == 2 ){
    $result = $db_handle->runBaseQuery("SELECT username FROM users ");
    foreach ($result as $row){
        $username = $row["username"];
        if(isset($_GET[$username.'-grant'])) {
            $db_handle->runBaseQuery("UPDATE users SET privilege = 1 WHERE username = '$username'");
            $db_handle->runBaseQuery("DELETE FROM request WHERE username = '$username'");
            header("Refresh:0; url=management.php");
        }
        if(isset($_GET[$username.'-remove'])) {
            $db_handle->runBaseQuery("UPDATE users SET privilege = 0 WHERE username = '$username'");
            header("Refresh:0; url=management.php");
        }
    }
}
?>