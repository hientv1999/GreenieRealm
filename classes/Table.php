<?php
require_once "DBController.php";
class Table {
    function createTable($table_name, $sql){
        $db_handle = new DBController();
        $link = $db_handle->connectDB();
        $query = "SELECT 1 FROM $table_name LIMIT 1";
        $result = mysqli_query($link, $query);
        if (empty($result)){
            if(!mysqli_query($link, $sql)){
               return "ERROR: Could not able to create table " . $table_name . ". Error: " . mysqli_error($link);
            } 
        }
    }
    
    function existTable_custom($db_name, $table_name){
        $db_handle = new DBController();
        $link = $db_handle->connectDB_custom($db_name);
        $query = "SELECT 1 FROM $table_name LIMIT 1";
        $result = mysqli_query($link, $query);
        if (empty($result)){
            return false;
        } 
        return true;
    }

    function createTable_custom($db_name, $table_name, $sql){//return true if existed
        $db_handle = new DBController();
        $link = $db_handle->connectDB_custom($db_name);
        mysqli_query($link, $sql);
    }

    function getMemberByUsername($username) {
        $db_handle = new DBController();
        $query = "Select * from users where username = ?";
        $result = $db_handle->runQuery($query, 's', array($username));
        return $result;
    }
    
}
?>