<?php
require_once "DBController.php";
class Auth {    
	function getTokenByUsername($username) {
	    $db_handle = new DBController();
	    $query = "Select * from tbl_token_auth where username = ?";
	    $result = $db_handle->runQuery($query, 's', array($username));
	    return $result;
    }
    
    function markAsExpired($tokenId) {
        $db_handle = new DBController();
        $query = "UPDATE tbl_token_auth SET is_expired = ? WHERE id = ?";
        $expired = 1;
        $result = $db_handle->update($query, 'ii', array($expired, $tokenId));
        return $result;
    }

    function removeExpired($tokenId){
        $db_handle = new DBController();
        $result = $db_handle->runBaseQuery("DELETE FROM tbl_token_auth WHERE id = '$tokenId'");
        return $result;
    }
    
    function insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date) {
        $db_handle = new DBController();
        $query = "INSERT INTO tbl_token_auth (username, password_hash, selector_hash, expiry_date) values (?, ?, ?,?)";
        $result = $db_handle->insert($query, 'ssss', array($username, $random_password_hash, $random_selector_hash, $expiry_date));
        return $result;
    }
    
    function update($query) {
        mysqli_query($this->conn,$query);
    }

    function getMemberByUsername($username) {
        $db_handle = new DBController();
        $query = "Select * from users where username = ?";
        $result = $db_handle->runQuery($query, 's', array($username));
        return $result;
    }
}

?>