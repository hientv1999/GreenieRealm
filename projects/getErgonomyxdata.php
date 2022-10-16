<?php
session_start();
header('Content-Type: application/json');
if ($_SESSION["ergonomyx_login"] == 1){
    if (isset($_GET['range'])){
        $range = $_GET['range'];
        print shell_exec("python3 ErgonomyxPlotter.py sitstandmove $range");
    } 
}
?>