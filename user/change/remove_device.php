<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect to login page
include "../../template/check_alive.php";
 
// Include config file
require_once "../../classes/Table.php";
$db_handle = new DBController();
$deviceAvatar = "";

// Check if the user has admin privilege, if not then redirect him to home page
include "../../template/check_privilege.php";

if($_SERVER["REQUEST_METHOD"] == "GET"){  
    if(!empty(trim($_GET["deviceType"])) && !empty(trim($_GET["deviceName"]))){
        $_SESSION["deleteDeviceType"] = trim($_GET["deviceType"]);
        $_SESSION["deleteDeviceName"] = trim($_GET["deviceName"]);
    }
} 
$deviceType = $_SESSION["deleteDeviceType"];
$deviceName = $_SESSION["deleteDeviceName"];
$result = $db_handle->runBaseQuery("SELECT filename FROM avatars WHERE user = '$deviceName'");
if (count($result) != 0 ){
    $deviceAvatar = $result[0]["filename"];
} else {
    switch ($_SESSION["deleteDeviceType"]) {
        case "Agrismart":
            $deviceAvatar = "Agrismart_avatar.PNG";
            break;
        // add more case here
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST"){  
    $password = "";
    $password_err = "";
    // Validate new password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter the password.";     
    } else {
        $password = trim($_POST["password"]);
        $current_user = $_SESSION["username"]; 
        $result = $db_handle->runBaseQuery("SELECT password FROM users WHERE username = '$current_user' LIMIT 1");
        if(!password_verify($password, $result[0]["password"])){
            $password_err = "Wrong current password";
        } else {
            // Remove device avatar
            $db_handle->runBaseQuery("DELETE FROM avatars WHERE user = '$deviceName'");
            $db_handle->custom_runBaseQuery($deviceType, "DROP TABLE $deviceName");
            $count = $db_handle->countTable($deviceType);
            if ($count == 0){
                $db_handle->deleteDB($deviceType);
            }
            header("location: ../home.php");
            exit();
        }
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?php echo htmlspecialchars($_SESSION["username"]); ?> - Remove Device</title> 
        <link href="../css/agrismart/device.css" rel="stylesheet" type="text/css">
        <link href="../../photo/favicon.png" rel="icon">
        <link href="../../css/styles.css" rel="stylesheet" />
        <link href="../../css/style.css" type="text/css" rel="stylesheet"> <!-- button css -->
        <!-- Bootstrap core CSS -->
        <link href="../../vendor/bootstrap/css/bootstrap-mainpage.css" rel="stylesheet">
        <link href="../../vendor/bootstrap/css/bootstrap-select-language.css" rel="stylesheet" type="text/css" />
        <link href="../../css/flag-icon.css" rel="stylesheet" type="text/css" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    
</head>
<body class="sb-nav-fixed">
    <?php include "../../template/topbar_setting.html" ?>
        <div id="layoutSidenav">
            <?php include "../../template/navbar_setting.html" ?>
            <div id="layoutSidenav_content">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                                <br>
                                <h2 style="text-align: center">Remove <?php echo $_SESSION["deleteDeviceType"]; ?> device </h2>
                                <p style="text-align: center">Please fill out this form to remove device <br> <?php echo explode("_", $_SESSION["deleteDeviceName"])[0] . " in ". explode("_", $_SESSION["deleteDeviceName"])[1]; ?></p>
                                <img src="../../photo/<?php echo $deviceAvatar; ?>" width="75px" height="75px" alt="Agrismart" style="display: block; margin: 0 auto; border-radius:50%; border: 2px solid #786450;">
                                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" id ="pw">
                                    <span style="font-size: 14px; color: #fc8f8f;"><?php echo $password_err; ?></span>
                                </div>
                                <div style="text-align: right; position: relative; bottom: 5px; white-space:nowrap; ">
                                <label class="checkbox bounce" style="display: inline-block; vertical-align: middle">
                                    <input type="checkbox" onclick="myFunction()" > 
                                    <svg viewBox="0 0 21 21">
                                        <polyline points="5 10.75 8.5 14.25 16 6"></polyline>
                                    </svg>
                                </label>
                                <h1 style ="font-size: 15px; display: inline-block; vertical-align: middle">Show password</h1>
                                </div>
                                <div class="form-group" style="text-align:center;">
                                    <input type="submit" class="btn btn-primary" value="Submit">
                                    <a class="btn btn-link" href="setting.php">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php include "../../template/footbar.php" ?>
            </div>    
        </div>
        <script src = "../../vendor/jquery/user_data.js"></script> <!-- get users' weather, location -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script> <!-- menu, lnaguage toggle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>  <!-- menu, lnaguage toggle -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script> <!-- menu icon on mobile -->
        <script src="../../vendor/jquery/scripts.js"></script> <!-- get curren year, highglight current tab -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <!-- <script src="assets/demo/chart-bar-demo.js"></script> -->
        <!-- <script src="jquery.js" type="text/javascript"></script> -->
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
            function myFunction() {
                var x = document.getElementById("pw");
                var y = document.getElementById("pw2");
                if (x.type === "password" && y.type === "password" ) {
                    x.type = "text";
                    y.type = "text";
                } else {
                    x.type = "password";
                    y.type = "password";
                }
            }
        </script>
        <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
        <script src="../../vendor/bootstrap/js/bootstrap-select-language.js" type="text/javascript"></script>
    </body>
</html>