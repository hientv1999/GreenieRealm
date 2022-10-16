<?php
$api_key_value = "TemperatureHumidity";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key= $sensor = $location = $temperature = $humidity = $waterlevel = $batterylevel = $chargingCurrent = $Watering = "";
    $api_key = process_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
        header('Content-Type: application/json');
        $data->offset = shell_exec("python3 getOffset.py");
        $sensor = process_input($_POST["sensor"]);
        $location = process_input($_POST["location"]);
        $temperature = process_input($_POST["Temperature"]);
        $humidity = process_input($_POST["Humidity"]);
        $waterlevel = process_input($_POST["WaterLevel"]);
        $batterylevel = process_input($_POST["BatteryLevel"]);
        $chargingCurrent = process_input($_POST["ChargingCurrent"]);
        $Watering = process_input($_POST["Watering"]);
        $time = gmdate('Y-m-d H:i:s');
        // Create connection
        $tableName = $sensor . "_" . $location;
        // Include config file
        require_once "../../../classes/Table.php";
        $db_handle = new DBController();
        $table = new Table();
        if ($db_handle->existDB("Agrismart") && $table->existTable_custom("Agrismart", $tableName)){
            $db_handle->custom_runBaseQuery("Agrismart", "INSERT INTO $tableName (Temperature, Humidity, WaterLevel, BatteryLevel, ChargingCurrent, Watering, Time) VALUES ('$temperature', '$humidity', '$waterlevel', '$batterylevel', '$chargingCurrent', '$Watering', '$time')");
        } else {
            $data->reset = "Yes";
        }
        echo json_encode($data);
        
    }
    else {
        echo "Wrong API Key provided.";
    }
}
else {
    echo "No data posted with HTTP POST.";
}

function process_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>