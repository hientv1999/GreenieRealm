<?php
class DBController {
	private $host = "localhost";
	private $user = "greenierealm";
	private $password = "raspberry";
	private $database = "website_account";
	private $conn;
	
    function __construct() {
        $this->conn = $this->connectDB();
	}	
	
	function connectDB() {
		$conn = mysqli_connect($this->host,$this->user,$this->password,$this->database);
		return $conn;
	}
	
    function connectDB_custom($db_name){
        $conn = mysqli_connect($this->host,$this->user,$this->password, $db_name);
		return $conn;
    }

    function createDB($db_name){
        $conn = new mysqli($this->host,$this->user,$this->password);
        $sql = "CREATE DATABASE IF NOT EXISTS $db_name";
        if ($conn->query($sql) === FALSE) {
            return false;
        }
        return true;
    }

    function existDB($db_name){
        $conn = mysqli_connect($this->host,$this->user,$this->password, $db_name);
        
        if ($conn == FALSE){
            return false;
        } else {
            return true;
        }
    }

    function deleteDB($db_name){
        $conn = new mysqli($this->host,$this->user,$this->password);
        $sql = "DROP DATABASE $db_name";
        $conn->query($sql);
    }

    function countTable($db_name){
        $conn = new mysqli($this->host,$this->user,$this->password);
        $sql = "SELECT Count(*) FROM <$db_name>.INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE'";
        $result = $conn->query($sql);
        return $result;
    }

    function getTable($db_name){
        $conn = new mysqli($this->host,$this->user,$this->password);
        $sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$db_name'";
        $result = $conn->query($sql);
        while($row=mysqli_fetch_assoc($result)) {
            $resultset[] = $row;
        }		
        if(!empty($resultset))
            return $resultset;
        return $resultset;
    }

    function runBaseQuery($query) {
        $result = mysqli_query($this->conn,$query);
        while($row=mysqli_fetch_assoc($result)) {
            $resultset[] = $row;
        }		
        if(!empty($resultset))
            return $resultset;
    }
    
    function custom_runBaseQuery($custom_database, $query) {
        $customized_conn = mysqli_connect($this->host,$this->user,$this->password,$custom_database);
        $result = mysqli_query($customized_conn,$query);
        while($row=mysqli_fetch_assoc($result)) {
        $resultset[] = $row;
        }		
        if(!empty($resultset))
            return $resultset;
    }
    
    function runQuery($query, $param_type, $param_value_array) {
        $sql = $this->conn->prepare($query);
        $this->bindQueryParams($sql, $param_type, $param_value_array);
        $sql->execute();
        $result = $sql->get_result();
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $resultset[] = $row;
            }
        }
        
        if(!empty($resultset)) {
            return $resultset;
        }
    }
    
    function bindQueryParams($sql, $param_type, $param_value_array) {
        $param_value_reference[] = & $param_type;
        for($i=0; $i<count($param_value_array); $i++) {
            $param_value_reference[] = & $param_value_array[$i];
        }
        call_user_func_array(array(
            $sql,
            'bind_param'
        ), $param_value_reference);
    }
    
    function insert($query, $param_type, $param_value_array) {
        $sql = $this->conn->prepare($query);
        $this->bindQueryParams($sql, $param_type, $param_value_array);
        $sql->execute();
    }
    
    function update($query, $param_type, $param_value_array) {
        $sql = $this->conn->prepare($query);
        $this->bindQueryParams($sql, $param_type, $param_value_array);
        $sql->execute();
    }
}
?>