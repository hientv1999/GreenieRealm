<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
include "../template/check_alive.php";
// Include config file
require_once "../classes/DBController.php";
require_once "../classes/Table.php";
$db_handle = new DBController();
$table = new Table();

$current_user = $_SESSION["username"];
$current_privilege = $_SESSION["privilege"];
$current_timezone = $_COOKIE["timezone"];
$current_date_structure = $_SESSION["date_structure"];
$deviceType = $otp = $location = $sensorName = "";
$deviceType_err = $otp_err = $location_err = $sensorName_err = "";
$msg = $_SESSION["msg"];

include "../template/check_guest.php";
// Get Current date, time
$current_time = time();
$current_date = date("Y-m-d H:i:s", $current_time);
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){    
    switch ($_SESSION["setup_mode"]){
        case "OTP":
            if(empty(trim($_POST["deviceType"]))){
                $deviceType_err = "Please select a device you want to connect.";
            } else {
                $deviceType = trim($_POST["deviceType"]);
                switch ($deviceType) {
                    case "Agrismart":
                        $output = shell_exec("python3 gardening/Agrismart/setup.py OTP");
                        if ($output != NULL){
                            $string_array = explode(PHP_EOL, $output);
                            // Calculate expiry date
                            $expiry_time = $current_time + 5*60;
                            $expiry_date = date("Y-m-d H:i:s", $expiry_time);
                            $msg = "OTP will expire at " . date($current_date_structure . " H:i:s", $expiry_time);
                            $_SESSION["msg"] = $msg;
                            for ($device = 0; $device < count($string_array) - 1; $device++){
                                $string = $string_array[$device];
                                $OTP = intval(explode(".", $string)[0]);
                                $MAC = explode(".", $string)[1];
                                $db_handle->runBaseQuery("INSERT INTO otp (OTP, MAC, expiry_date) VALUES ('$OTP', '$MAC', '$expiry_date')");
                                $_SESSION["setup_mode"] = "AGRISMART";
                            }
                        } else {
                            ?>
                            <script>alert("Cannot find any nearby Agrismart");</script>
                            <?php
                        }
                        break;

                    case "Garage":
                        // Creating a database named Access if not existed
                        $db_handle->createDB("Access");
                        $tableName = "Garage";
                        if (! $table->existTable_custom("Access", $tableName)){
                            $dataTable_sql = "CREATE TABLE $tableName (
                                id INT unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
                                DeviceName VARCHAR(32) NOT NULL UNIQUE, 
                                State INT(1) NOT NULL DEFAULT 0,
                                Time timestamp NOT NULL
                            )";
                            $table->createTable_custom("Access", $tableName, $dataTable_sql);
                        }
                        $deviceName = "Garage 1";
                        $time = gmdate('Y-m-d H:i:s');
                        $db_handle->custom_runBaseQuery("Access", "INSERT INTO $tableName (DeviceName, Time) VALUES ('$deviceName', '$time')");
                        break;
                }
            }
            break;
        
        case "AGRISMART":
            include "gardening/Agrismart/setup.php";
            break;
    }   
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" >
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title><?php echo htmlspecialchars($_SESSION["username"]); ?> - Connect</title> 
        <style>
            @import url(https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900);

            html,body {
            margin: 0;
            padding: 0;
            font-family:'Lato', sans-serif;
            }
            .loader {
            width: 100px;
            height: 80px;
            position: absolute;
            top: 0; right: 0; left: 0; bottom: 0;
            margin: auto;
            }
            .loader .image {
            width: 100px;
            height: 160px;
            font-size: 40px;
            text-align: center;
            transform-origin: bottom center;
            animation: 3s rotate infinite;
            opacity: 0;
            }
            .loader span {
            display: block;
            width: 100%;
            text-align: center;
            position: absolute;
            bottom: 0;
            }

            @keyframes rotate{
            0% {
                transform: rotate(90deg);
            }
            10% {
                opacity: 0;
            }
            35% {
                transform: rotate(0deg);
                opacity: 1;
            }
            65% {
                transform: rotate(0deg);
                opacity: 1;
            }
            80% {
                opacity: 0;
            }
            100% {
                transform: rotate(-90deg);
            }
            }
        </style>
        <link href="../photo/favicon.png" rel="icon">
        <link href="../css/styles.css" rel="stylesheet"> <!-- css for navbar -->
        
        <link rel="stylesheet" type="text/css" href="../css/connect/util.css">
	    <link rel="stylesheet" type="text/css" href="../css/connect/main.css">
        <!-- Bootstrap core CSS -->
        <link href="../vendor/bootstrap/css/bootstrap-home.css" rel="stylesheet" type="text/css"> <!-- main font -->
        <link href="../vendor/bootstrap/css/bootstrap-select-language.css" rel="stylesheet" type="text/css"> <!--language dropout menu -->
        <link href="../css/flag-icon.css" rel="stylesheet" type="text/css"> <!--country flags -->
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    </head>
    <body >        
        <?php include "../template/topbar.html" ?>
        <div id="layoutSidenav">
            <?php include "../template/navbar.html" ?>
            <div id="layoutSidenav_content">
                <img id="img" style="max-width: 100px; position:absolute; top:50%; left: 50%; transform: translate(-50%, -50%);"  src="" >
                <div id="content" class="container-login100">
                    <div class="wrap-login100">
                        <?php 
                        switch ($_SESSION["setup_mode"]) {
                            case "OTP": ?>
                                <div class="login100-pic js-tilt" data-tilt>
                                    <img src="../photo/coming_soon.jpg" alt="IMG">
                                </div>
                                <form class="login100-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <span class="login100-form-title">
                                        <strong>Connect new device</strong>
                                        <p style = "font-size: 16px"> Choose your device name </p>
                                        <p style="font-size: 14px; color: #fc8f8f; text-align: center;" ><?php echo $deviceType_err ?> </p>
                                        <p><select  class="selectpicker" style="align-items: center" data-width="fit" name = "deviceType" >
                                            <option selected disabled hidden style="display:none;">Device</option>
                                            <option value="Agrismart">Agrismart</option>
                                            <option value="Garage">Garage Remote</option>
                                        </select></p>
                
                                        <div class="container-login100-form-btn">
                                            <input id="submit-button" type="submit" value="Start" class="login100-form-btn">
                                        </div>
                                    </span>
                                </form>
                                <?php
                                break;

                            case "AGRISMART":
                                include "gardening/Agrismart/setup_html.php";
                                break;
                        } ?>
                    </div>
                </div>
                <!-- Add thing here -->
            <?php include "../template/footbar.php" ?>
            </div>
        </div>
        <script src="../vendor/jquery/connect/jquery-3.2.1.min.js"></script>
        <script src="../vendor/jquery/connect/tilt.jquery.min.js"></script>
        <script src="../vendor/bootstrap/js/main.js"></script> <!-- input error -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script> <!-- menu, language toggle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>  <!-- menu, language toggle -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script> <!-- menu icon on mobile -->
        <script src="../vendor/jquery/scripts.js" type="text/javascript"></script> <!-- get curren year, highglight current tab -->
        <script type="text/javascript">
            function googleTranslateElementInit() {
                new google.translate.TranslateElement({ pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false }, 'google_translate_element');
            }
            
            function translateLanguage(lang) {
                googleTranslateElementInit();
                var $frame = $('.goog-te-menu-frame:first');
                if (!$frame.size()) {
                    alert("Error: Could not find Google translate frame.");
                    return false;
                }
                $frame.contents().find('.goog-te-menu2-item span.text:contains(' + lang + ')').get(0).click();
                return false;
            }

            $(function(){
                $('.selectpicker').selectpicker();
            });
            
            $("#submit-button").on("click", function(){
                document.getElementById("content").style.opacity = "0.3";
                document.getElementById("img").src = "../photo/load-otp.gif";
            });
        </script>
        <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
        <script src="../vendor/bootstrap/js/bootstrap-select-language.js" type="text/javascript"></script>
        
    </body>
</html>