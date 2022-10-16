<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
include "../template/check_alive.php";
// Include config file
require_once "../classes/Table.php";
$db_handle = new DBController();
$table = new Table();

// require_once "../config.php";
// Define variables and initialize with empty values
$city = $city_err = $city_label = "";
$msg = "";
$list_city = [];
$username = $_SESSION["username"];
$current_privilege = $_SESSION["privilege"];
$current_timezone = $_SESSION["timezone"];

// // Prepare a select statement
// $sql2 = "SELECT id, city FROM $username";
// if($stmt2 = mysqli_prepare($link, $sql2)){
//     if(mysqli_stmt_execute($stmt2)){
//         $result = mysqli_stmt_get_result($stmt2);
//         while ($row = mysqli_fetch_array($result, MYSQLI_NUM)){
//             array_push($list_city, $row[1]);
//         }
//     } else {
//         $msg = "Oops! Something went wrong. Please try again later.";
//     }
//     mysqli_stmt_close($stmt2);
// }

// Processing form data when form is submitted
// if($_SERVER["REQUEST_METHOD"] == "POST"){
//     // Check if username is empty
//     if(empty(trim($_POST["city"]))){
//         $city_err = "Please enter city name.";
//     } else{
//         $city = trim($_POST["city"]);
//         $city=strtolower(preg_replace('~[^A-Za-z-]~','',$city)); // only letters and - passed
//     }

//     // Add city to the list city
//     if(empty($city_err)){
//         // Prepare a select statement
//         $sql3 = "INSERT INTO $username (city) VALUES (?)";
//         if($stmt3 = mysqli_prepare($link, $sql3)){
//             mysqli_stmt_bind_param($stmt3, "s", $param_city);
//             $param_city = $city;
//             if(mysqli_stmt_execute($stmt3)){
                
//             } else {
//                 $msg = "Oops! Something went wrong. Please try again later.";
//             }
//             mysqli_stmt_close($stmt3);
//         }
//     }
// }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?php echo htmlspecialchars($_SESSION["username"]); ?> - Weather</title> 
        <link href="../photo/favicon.png" rel="icon">
        <link href="../css/styles.css" rel="stylesheet"> <!-- css for navbar -->
        <!-- Bootstrap core CSS -->
        <link href="../vendor/bootstrap/css/bootstrap-home.css" rel="stylesheet" type="text/css"> <!-- main font -->
        <link href="../vendor/bootstrap/css/bootstrap-select-language.css" rel="stylesheet" type="text/css"> <!-- language dropout menu -->
         <link href="../css/flag-icon.css" rel="stylesheet" type="text/css"> <!--country flags -->
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous">
    </head>
    <body class="sb-nav-fixed">
        <?php include "../template/topbar.html" ?>
        <div id="layoutSidenav">
            <?php include "../template/navbar.html" ?>
            <div id="layoutSidenav_content">
               
                <div class="container-fluid" style="position:relative; z-index:200">
                    <br>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Weather by Weather Widget</li>
                    </ol>
                </div>
                
                <div class="container-fluid" >
                    <!-- <div class="row justify-content-center" style="position: relative; z-index:1;">
                        <script src="https://apps.elfsight.com/p/platform.js" defer></script>
                        <div class="elfsight-app-f0aa824a-b9ad-4ca2-87a0-cd866ed65f60"></div> -->
                    <!--Edit here:  
                    https://apps.elfsight.com/panel/applications/weather/?utm_source=clients&utm_medium=user-panel&utm_campaign=edit-widget&utm_content=weather&utm_term=dt63npqi2g9.p18.rt3.io
                    -->
                    <!-- </div>
                    <div class="container-fluid" style = "position:relative; top:-50px; z-index:100"> cover logo                   -->
                        <!-- <svg width="1000" height="50" style="display:block;margin:auto">
                            <rect width="1000" height="50" style="fill:rgb(255,255,255);" /> 
                        </svg>
                    </div> -->
                    <a class="weatherWidget"></a>
                </div>
                
                <div class="container-fluid" style="position:relative; top:20px">
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Local weather</li>
                    </ol>
                </div>
                <?php include "../template/footbar.php" ?>
            </div>
        </div>
        <script src = "../vendor/jquery/user_data.js"></script> <!-- get users' weather, location -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script> <!-- menu, lnaguage toggle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>  <!-- menu, lnaguage toggle -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script> <!-- menu icon on mobile -->
        <script src="../vendor/jquery/scripts.js"></script> <!-- get curren year, highglight current tab -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="../Agrismart/Chart.js"></script>
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
        </script>
        <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
        <script src="../vendor/bootstrap/js/bootstrap-select-language.js" type="text/javascript"></script>
    </body>
</html>








