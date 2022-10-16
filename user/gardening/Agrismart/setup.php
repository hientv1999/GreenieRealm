<?php
if (empty(trim($_POST["otp"]))){
    $otp_err = "Please enter the OTP displayed on your device screen";
} else if (strlen(trim($_POST["otp"])) > 4 && !is_int(trim($_POST["otp"]))) {
    $otp_err = "Invalid OTP";
} else {
    $otp = trim($_POST["otp"]);
}
if (empty(trim($_POST["sensorName"]))){
    $sensorName_err = "Please name this device";
} else if (strlen(trim($_POST["sensorName"])) > 32) {
    $sensorName_err = "Device name is too long (32 characters max)";
} else if (!ctype_alnum(trim($_POST["sensorName"]))){
    $sensorName_err = "Device name must contain only alphabets and/or numbers. Use capitalization to seperate words";
} else {
    $sensorName = trim($_POST["sensorName"]);
}   
if (empty(trim($_POST["location"]))){
    $location_err = "Please specify the location you place this device at";
} else if (strlen(trim($_POST["location"])) > 32) {
    $location_err = "Device location is too long (32 characters max)";
} else if (!ctype_alnum(trim($_POST["location"]))){
    $location_err = "Device location must contain only alphabets and/or numbers. Use capitalization to seperate words";
} else {
    $location = trim($_POST["location"]);
}
if (empty($otp_err) && empty($sensorName_err) && empty($location_err)){
    // Creating a database named Agrismart if not existed
    $db_handle->createDB("Agrismart");
    // if table exists, yield error
    $tableName = $sensorName . "_" . "$location";
    if ($table->existTable_custom("Agrismart", $tableName)){
        $msg = "A device with similar name and location is currently being used. Please choose a different name or location for this device.";
    } else {
        // check OTP
        $result = $db_handle->runBaseQuery("SELECT MAC, expiry_date FROM otp WHERE OTP = '$otp'");
        if (count($result) == 0){
            $msg = "Wrong OTP";
        } else {
            if ($current_date >= $result[0]["expiry_date"]){
                $msg = "Expired OTP. Please restart the setup process";
            } else {
                $MAC = $result[0]["MAC"];
                $OTP_MAC = $otp . "." . $MAC;
                $output = shell_exec("python3 gardening/Agrismart/setup.py SEND $OTP_MAC $sensorName $location");
                if ($output != NULL){
                    $msg = $output;
                } else {
                    //create data table
                    $dataTable_sql = "CREATE TABLE $tableName (
                        id INT unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
                        Temperature FLOAT(4,2) NOT NULL,
                        Humidity FLOAT(4,2) NOT NULL,
                        WaterLevel INT(3) NOT NULL,
                        BatteryLevel INT(3) NOT NULL,
                        ChargingCurrent INT(4) NOT NULL,
                        Watering INT(1) NOT NULL,
                        Time timestamp NOT NULL
                    )";
                    $table->createTable_custom("Agrismart", $tableName, $dataTable_sql);
                    // clear table otp
                    $db_handle->runBaseQuery("TRUNCATE TABLE otp");
                    // clear session setup mode
                    $_SESSION["setup_mode"] = "OTP";
                    $_SESSION["msg"] = "";
                    ?>
                    <script>
                        alert("Setup successfully");
                        window.location.href = "home.php";
                    </script>
                    <?php
                }
            }
        } 
    }
}
?>
