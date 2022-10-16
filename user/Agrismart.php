<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
include "../template/check_alive.php";

// Include config file
require_once "../classes/Table.php";
$db_handle = new DBController();
$table = new Table();

$current_user = $_SESSION["username"];
$current_privilege = $_SESSION["privilege"];
$current_timezone = $_COOKIE["timezone"];
$current_date_structure = $_SESSION["date_structure"];

// Check if image file is a actual image or fake image
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $file_msg = "";
    if(getimagesize($_FILES["fileToUpload"]["tmp_name"]) == false) {
        $file_msg .= "Cannot upload file to server. Try different image or try again later.";
    } else {
        $target_dir = "../photo/";
        $target_file = basename($_FILES["fileToUpload"]["name"]);
        // ensure file format is valid
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            $file_msg .= "Only JPG, JPEG, PNG & GIF files are allowed. ";
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 64000000) {
            $file_msg .= "Max file size is 64MB.";
        }
        // ensure filename isn't duplicated
        while (file_exists($target_dir . $target_file) && strlen($target_file) <= 253){
            $new_name = basename($target_file, ".".$imageFileType) . "-1";
            $target_file = $new_name . "." . $imageFileType;
        }
        // ensure file name isn't longer than 255 characters
        if (strlen($target_file) >255){
            $file_msg = "Max file name is 255 characters including its extension";
        }
        if (empty($file_msg) && move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $target_file) && !empty(trim($_POST["Agrismart"]))){
            $file_msg .= "Image has been uploaded";
            $user = trim($_POST["Agrismart"]);
            $result = $db_handle->runBaseQuery("SELECT filename FROM avatars WHERE user = '$user'");
            if (count($result) == 0 ){
                $db_handle->runBaseQuery("INSERT INTO avatars (user, filename) VALUES ('$user', '$target_file')");
            } else {
                // delete old avatar
                $old_target_file = $result[0]["filename"];
                unlink($target_dir . $old_target_file);
                $db_handle->runBaseQuery("UPDATE avatars SET filename = '$target_file' WHERE user = '$user'");
            }
            
            
            
        } else {
            $file_msg .= "Cannot upload your file to server";
        }
    }   
    echo "<script>alert(\"$file_msg\")</script>";
    header(htmlspecialchars($_SERVER["PHP_SELF"]));
}

$list = $db_handle->getTable("Agrismart");
$device_locations = array();

foreach ($list as $element){
    $location_individual = explode("_", $element["TABLE_NAME"])[1];
    array_push($device_locations, $location_individual);
}
$device_locations = array_unique($device_locations);
$device_names = array();
$device_avatars = array();
foreach ($device_locations as $location){
    $buffer_name = array();
    $buffer_avatar = array();
    foreach ($list as $element){
        $location_individual = explode("_", $element["TABLE_NAME"])[1];
        if ($location_individual == $location){
            $name_individual = explode("_", $element["TABLE_NAME"])[0];
            array_push($buffer_name, $name_individual);
            // find avatar
            $user = $element["TABLE_NAME"];
            $result = $db_handle->runBaseQuery("SELECT filename FROM avatars WHERE user = '$user'");
            if (count($result) == 0){
                array_push($buffer_avatar, "Agrismart_avatar.PNG");
            } else {
                array_push($buffer_avatar, $result[0]["filename"]);
            }
        }
    }
    array_push($device_names, $buffer_name);
    array_push($device_avatars, $buffer_avatar);
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
        <title><?php echo htmlspecialchars($_SESSION["username"]); ?> - Agrismart</title> 
        <link href="../css/agrismart/device.css" rel="stylesheet" type="text/css">
        <link href="../photo/favicon.png" rel="icon">
        <link href="../css/styles.css" rel="stylesheet"> <!-- css for navbar -->
        <!-- Bootstrap core CSS -->
        <link href="../vendor/bootstrap/css/bootstrap-home.css" rel="stylesheet" type="text/css"> <!-- main font -->
        <link href="../vendor/bootstrap/css/bootstrap-select-language.css" rel="stylesheet" type="text/css"> <!--language dropout menu -->
        <link href="../css/flag-icon.css" rel="stylesheet" type="text/css"> <!--country flags -->
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous">
        
    </head>
    <body class="sb-nav-fixed" > 
        <?php include "../template/topbar.html" ?>
        <div id="layoutSidenav">
            <?php include "../template/navbar.html" ?>
            <div id="layoutSidenav_content">
                <br>
                <div class="container-fluid">
                    <?php 
                    for ($i=0; $i<count($device_locations); $i++){
                        ?>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active"><?php echo $device_locations[$i]; ?></li>
                        </ol>
                        <div class="row">
                            <?php
                            for ($j=0; $j<count($device_names[$i]); $j++){
                                $tableName = $device_names[$i][$j] . "_" . $device_locations[$i]; 
                                $result = $db_handle->custom_runBaseQuery("Agrismart", "SELECT Temperature, Humidity, WaterLevel, BatteryLevel, Time FROM $tableName ORDER BY id DESC LIMIT 1");
                                ?>
                                <div class="frame">
                                    <div class="center">
                                            <div class="profile">
                                                <div class="image">
                                                    <div class="circle-1"></div>
                                                    <div class="circle-2"></div>
                                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                                                        <label class="edit">
                                                            Edit
                                                            <input type="hidden" name="Agrismart" value="<?php echo $tableName?>" style="display: none;" >
                                                            <input type="file" name="fileToUpload" id="fileToUpload" onchange="form.submit()" style="display: none;">
                                                        </label>
                                                    </form>
                                    			    <img src="../photo/<?php echo $device_avatars[$i][$j]; ?>" width="100%" height="100%" alt="Agrismart">

                                                </div>

                                                <div class="name"><?php echo $device_names[$i][$j]; ?></div>
                                                <?php
                                                    $current_time = time();
                                                    $sec_delay = $current_time - date("Z") - strtotime($result[0]["Time"]);
                                                    if ($sec_delay < 60){
                                                        if ($sec_delay < 2){
                                                            echo "<div class=\"location\">$sec_delay second ago</div>";
                                                        } else {
                                                            echo "<div class=\"location\">$sec_delay seconds ago</div>";
                                                        }
                                                    } else {
                                                        $min_delay = intval($sec_delay / 60);
                                                        if ($min_delay < 60){
                                                            if ($min_delay < 2){
                                                                echo "<div class=\"location\">$min_delay minute ago</div>";
                                                            } else {
                                                                echo "<div class=\"location\">$min_delay minutes ago</div>";
                                                            }
                                                        } else {
                                                            $hour_delay = intval($sec_delay/3600);
                                                            if ($hour_delay < 24){
                                                                if ($hour_delay < 2){
                                                                    echo "<div class=\"location\">$hour_delay hour ago</div>";
                                                                } else {
                                                                    echo "<div class=\"location\">$hour_delay hours ago</div>";
                                                                }
                                                            } else {
                                                                $day_delay = intval($sec_delay/24/3600);
                                                                if ($day_delay < 2){
                                                                    echo "<div class=\"location\">$day_delay day ago</div>";
                                                                } else {
                                                                    echo "<div class=\"location\">$day_delay days ago</div>";
                                                                }
                                                            }
                                                        }
                                                    }     
                                                ?>
                                                <div class="actions">
                                                    <form action = "change/remove_device.php" method = "get">
                                                        <input type="hidden" name="deviceType" value="Agrismart" style="display: none;" >
                                                        <input type="hidden" name="deviceName" value="<?php echo $tableName?>" style="display: none;" >
                                                    <?php
                                                        $current_time = time();
                                                        $offline_threshold = date("Y-m-d H:i:s", $current_time - date("Z") - (10*60));
                                                        if ($result[0]["Time"] > $offline_threshold){
                                                            echo "<button class =\"btn\"  data-back=\"Remove\" style=\"--text-color: #4bca46;\" data-front=\"Online\"></button>";
                                                        } else {
                                                            echo "<button class =\"btn\"  data-back=\"Remove\" style=\"--text-color: red;\" data-front=\"Offline\"></button>";
                                                        }
                                                    ?>
                                                    </form>
                                                </div>
                                            </div>
                                            
                                            <div class="stats">
                                                <?php 
                                                    $temperature = $result[0]["Temperature"];
                                                    $humidity = $result[0]["Humidity"];
                                                    $waterLevel = $result[0]["WaterLevel"];
                                                    $batteryLevel = $result[0]["BatteryLevel"];
                                                ?>
                                                <div class="box">
                                                    <span class="value"><?php echo $temperature . " °C"; ?></span>
                                                        <?php
                                                        if ($temperature >30){
                                                            echo "<span style=\"color: red\" class=\"parameter\">";
                                                            echo "<i class=\"fa fa-temperature-high \"></i>" ;  
                                                        } else if ($temperature < 10){
                                                            echo "<span style=\"color: blue\" class=\"parameter\">";
                                                            echo "<i class=\"fa fa-temperature-low \"></i>" ;  
                                                        } else {
                                                            echo "<span class=\"parameter\">";
                                                            echo "<i class=\"fa fa-temperature-low \"></i>" ;  
                                                        }
                                                        echo "</span>";
                                                        ?>
                                                </div>
                                                <div class="box">
                                                    <span class="value"><?php echo $humidity . " %"; ?></span>
                                                    <?php
                                                    if ($humidity > 60 || $humidity < 30){
                                                        echo "<span style=\"color: red\" class=\"parameter\">";
                                                    } else {
                                                        echo "<span class=\"parameter\">";
                                                    }
                                                    echo "<i class=\"fas fa-hand-holding-water mr-1\"></i>";
                                                    echo "</span>";
                                                    ?>
                                                </div>
                                                <div class="box">
                                                    <span class="value"><?php echo $waterLevel . " %"; ?></span>
                                                    <?php
                                                    if ($waterLevel < 10) {
                                                        echo "<span style = \"color: red \" class=\"parameter\">";
                                                    } else {
                                                        echo "<span class=\"parameter\">";
                                                    }
                                                    echo "<i class=\"fas fa-water\"></i>";
                                                    echo "</scan>";
                                                    ?>
                                                </div>
                                                <div class="box">
                                                    <span class="value"><?php echo $batteryLevel . " %"; ?></span>
                                                    <?php
                                                        if ($batteryLevel < 10) {
                                                            echo "<span style = \"color: red \" class=\"parameter\">";
                                                            echo "<i class=\"fas fa-battery-empty\"></i>";
                                                        } else if ($batteryLevel < 37.5) {
                                                            echo "<span class=\"parameter\">";
                                                            echo "<i class=\"fas fa-battery-quarter\"></i>";
                                                        } else if ($batteryLevel < 62.5) {
                                                            echo "<span class=\"parameter\">";
                                                            echo "<i class=\"fas fa-battery-half\"></i>";
                                                        } else if ($batteryLevel < 87.5) {
                                                            echo "<span class=\"parameter\">";
                                                            echo "<i class=\"fas fa-battery-three-quarters\"></i>";
                                                        } else {
                                                            echo "<span class=\"parameter\">";
                                                            echo "<i class=\"fas fa-battery-full\"></i>";
                                                        }
                                                        echo "</scan>";
                                                    ?>
                                                    
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            
                                
                        <?php    
                        }
                        
                    }
                    ?>
                    </div>                  
                </div>
                <?php include "../template/footbar.php" ?>
            </div>
        </div>
        <script src = "../vendor/jquery/user_data.js"></script>  <!-- get users' weather, location -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script> <!-- menu, language toggle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>  <!-- menu, language toggle -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script> <!-- menu icon on mobile -->
        <script src="../vendor/jquery/scripts.js" type="text/javascript"></script> <!-- get curren year, highglight current tab -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js" crossorigin="anonymous"></script>
        <script src="gardening/Agrismart/Chart.js"></script>
        <script src="../jquery.js" type="text/javascript"></script> <!--the chart-->
        <!-- <script src="assets/demo/chart-bar-demo.js"></script> -->
        <!-- <script src="vendor/jquery/table.js" crossorigin="anonymous"></script> -->
        <!-- <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script> -->
        <!-- <script src="assets/demo/datatables-demo.js"></script> -->
        <!-- <script src="bootstrap-2.js" type="text/javascript"></script> -->
    
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

            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }

        </script>
        <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
        <script src="../vendor/bootstrap/js/bootstrap-select-language.js" type="text/javascript"></script>
        
    </body>