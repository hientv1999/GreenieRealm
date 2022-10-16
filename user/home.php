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
$total_user = 0;
$admin = 0;
$outdoor_device = "";

// clear table otp
$db_handle->runBaseQuery("TRUNCATE TABLE otp");
// clear session setup mode
$_SESSION["setup_mode"] = "OTP";

//if timezone is never updated, then do it now
if ($_SESSION["timezone"] != $current_timezone){
    //update timezone
    $db_handle->runBaseQuery("UPDATE users SET timezone = '$current_timezone' WHERE username = '$current_user'");
    $_SESSION["timezone"] = $current_timezone;
}

//check if this is the first admin
$result = $db_handle->runBaseQuery("SELECT privilege FROM users ");
$total_user = count($result);
foreach ($result as $user){
    if ($user["privilege"] == 2){
        $admin = 1;
    }
}

if ($admin == 0){
    //assign that account with admin privilege
    $db_handle->runBaseQuery("UPDATE users SET privilege = 2 WHERE username = '$current_user' AND active = 1");
    ?>
    <script>
        alert("You are the admin");
    </script>
    <?php
    header("Refresh:0");
}

// retrieve outdoor device to display data
$result = $db_handle->runBaseQuery("SELECT outdoor_device FROM settings WHERE username = '$current_user'");
$outdoor_device = $result[0]["outdoor_device"];
// not set yet
if ($outdoor_device == ""){
    $list_outdoor_device = $db_handle->getTable("Agrismart");
    $outdoor_device = $list_outdoor_device[0]["TABLE_NAME"];
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
        <title><?php echo htmlspecialchars($_SESSION["username"]); ?> - Greenie Realm</title> 
        <link href="../photo/favicon.png" rel="icon">
        <link href="../css/styles.css" rel="stylesheet"> <!-- css for navbar -->
        <!-- Bootstrap core CSS -->
        <link href="../vendor/bootstrap/css/bootstrap-home.css" rel="stylesheet" type="text/css"> <!-- main font -->
        <link href="../vendor/bootstrap/css/bootstrap-select-language.css" rel="stylesheet" type="text/css"> <!--language dropout menu -->
        <link href="../css/flag-icon.css" rel="stylesheet" type="text/css"> <!--country flags -->
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous">
        
    </head>
    <body class="sb-nav-fixed"> 
        <?php include "../template/topbar.html" ?>
        <div id="layoutSidenav">
            <?php include "../template/navbar.html" ?>
            <div id="layoutSidenav_content">
                <div class="container-fluid">
                    <br>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard </li>
                    </ol>
                    <a class="weatherWidget"></a>
                    <br>
                    <!--
                        Edit here: https://weatherwidget.io/
                        -->
                    <div class="row">
                        <div class="col-xl-4 col-md-6 col-sm-12" >
                            <div class="card  text-white mb-2" >
                                <div class="card-body bg-primary" style="color: black; text-align: center;">Recent Tasks <i class="fas fa-tasks mr-1"></i></div>
                                <div class="card-footer bg-white d-flex align-items-center justify-content-between">
                                    <a class=" stretched-link" style="color: black" href="#">Watering on balcony</a>
                                    <div class="small" style="color: black"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-sm-12" >
                            <div class="card text-white mb-2">
                                <div class="card-body bg-warning" style="color: black; text-align: center;">Warning <i class="fas fa-angry mr-1"></i></div>
                                <div class="card-footer bg-white d-flex align-items-center justify-content-between">
                                    <a class=" stretched-link" style="color: black" href="#">Refill garden tank</a>
                                    <div class="small" style="color: black"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-sm-12" >
                            <div class="card  text-white mb-4">
                                <div class="card-body bg-danger" style="color: black; text-align: center;">Danger <i class="fas fa-dumpster-fire mr-1"></i></div>
                                <div class="card-footer bg-white d-flex align-items-center justify-content-between">
                                    <a class=" stretched-link" style="color: black" href="#">None</a>
                                    <div class="small" style="color: black"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div id = "tableName" value = <?php echo $outdoor_device;?> style="display:none" > </div>
                        <div class="col-xl-4 col-md-6 col-sm-12">
                            <div class="card-header">
                                <i class="fas fa-temperature-low mr-1"></i>
                                Outdoor Temperature
                            </div>
                            <div id="no-data-temperature" style="display:none"> <p style="text-align: center"> <br>Not enough data to display </p> </div>
                            <div class="card-body"><canvas id="OutdoorTemperatureLineChart" width="100%" height="40"></canvas></div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-sm-12">
                            <div class="card-header">
                                <i class="fas fa-hand-holding-water mr-1"></i>
                                Outdoor Humidity
                            </div>
                            <div id="no-data-humidity" style="display:none"> <p style="text-align: center"> <br>Not enough data to display </p> </div>
                            <div class="card-body"><canvas id="OutdoorHumidityLineChart" width="100%" height="40"></canvas></div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-sm-12">
                            <div class="card-header">
                                <i class="fas fa-solar-panel mr-1"></i>
                                Solar Energy Power
                            </div>
                            <div class="card-body"><canvas id="myPieChart" width="100%" height="40"></canvas></div>
                        </div>
                    </div>
                    <div class="row">
                        <!--Add new graph here -->
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
        </script>
        <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
        <script src="../vendor/bootstrap/js/bootstrap-select-language.js" type="text/javascript"></script>
        
    </body>
</html>
