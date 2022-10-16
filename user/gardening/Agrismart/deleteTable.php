<?php
$api_key_value = "TemperatureHumidity";
if ($_SERVER["REQUEST_METHOD"] == "POST") {   
    $api_key= $sensorName = $location = "";
    $api_key = process_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
        $sensorName = process_input($_POST["sensorName"]);
        $location = process_input($_POST["location"]);
        $table_name = $sensorName . "_" . $location;
        // Include config file
        require_once "../../../classes/DBController.php";
        $db_handle = new DBController();
        $db_handle->custom_runBaseQuery("Agrismart", "DROP TABLE $table_name");
        $count = $db_handle->countTable("Agrismart");
        if ($count == 0){
            $db_handle->deleteDB("Agrismart");
        }
    }
}

function process_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>