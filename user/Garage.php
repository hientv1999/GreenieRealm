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
$admin = 0;

include "../template/check_guest.php";

$result = $db_handle->custom_runBaseQuery("Access", "SELECT DeviceName, State, Time FROM Garage ");
$deviceName = $state = $time = [];
foreach ($result as $device){
    array_push($deviceName, $device["DeviceName"]);
    array_push($state, $device["State"]);
    array_push($time, $device["Time"]);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (isset($_POST["page_form"])){
        for ($i=0; $i<count($result); $i++){
            $name = $deviceName[$i];
            if (str_replace(' ','',$name) == $_POST["page_form"]){
                // Run the RF script
                $output = shell_exec("python3 access/garage_door.py 2>&1");
                ?> <script> <?php echo $output; ?> </script> <?php
                if (empty($output)){
                    $state[$i] = $state[$i] ^ 1;
                    $time[$i] = gmdate('Y-m-d H:i:s');
                    $db_handle->custom_runBaseQuery("Access", "UPDATE Garage SET State = '$state[$i]', Time = '$time[$i]' WHERE DeviceName = '$name'");

                }
                break;
            }
            
        }   
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
        <title><?php echo htmlspecialchars($_SESSION["username"]); ?> - Garage</title> 
        <style>
            @keyframes stripe-slide {
                0% {
                    background-position: 0% 0;
                }
                100% {
                    background-position: 100% 0;
                }
            }
            
            
            .btn:-moz-focus-inner {
                padding: 0;
                border: 0;
                
            }
            .btn--stripe {
                overflow: hidden;
                position: relative;
                height: 50px;
                font-size: 20px !important;
            }
            .btn--stripe:after {
                content: '';
                display: block;
                height: 7px;
                width: 100%;
                background-image: repeating-linear-gradient(45deg, #666, #666 1px, #F9E26F 2px, #F9E26F 5px);
                -webkit-backface-visibility: hidden;
                backface-visibility: hidden;
                border-top: 1px solid #666;
                position: absolute;
                left: 0;
                bottom: 0;
                background-size: 7px 7px;
            }
            .btn--stripe:hover {
                background-color: #666;
                color: #fff;
                border-color: #666;
            }
            .btn--stripe:hover:after {
                background-image: repeating-linear-gradient(45deg, #666, #666 1px, #F9E26F 2px, #F9E26F 5px);
                border-top: 1px solid #F9E26F;
                animation: stripe-slide 12s infinite linear forwards;
            }
            .btn--large {
                
                width: min(50%, 200px);
            }
        </style>
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
                <div class="container-fluid" style="text-align: center">
                    <?php
                    for ($i=0; $i<count($result); $i++){
                    ?>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active"> <?php echo $deviceName[$i]; ?> </li>
                        </ol>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id=<?php echo $i; ?>>
                            <input type="hidden" name = "page_form" value = <?php echo str_replace(' ','',$deviceName[$i]); ?> >
                            <button type="submit"  form = <?php echo $i; ?> class="btn btn--stripe btn--large ">
                            <?php
                                if ($state[$i] == 0){
                                    echo "OPEN";
                                } else {
                                    echo "CLOSE";
                                }
                            ?>
                            </button>
                        </form>
                        <?php
                            if ($state[$i] == 0){
                                echo "<p> Last close: ";
                            } else {
                                echo "<p> Last open: ";
                            }
                            $current_time = time();
                            $sec_delay = $current_time - date("Z") - strtotime($time[$i]);
                            if ($sec_delay < 60){
                                if ($sec_delay < 2){
                                    echo "$sec_delay second ago";
                                } else {
                                    echo "$sec_delay seconds ago";
                                }
                            } else {
                                $min_delay = intval($sec_delay / 60);
                                if ($min_delay < 60){
                                    if ($min_delay < 2){
                                        echo "$min_delay minute ago";
                                    } else {
                                        echo "$min_delay minutes ago";
                                    }
                                } else {
                                    $hour_delay = intval($sec_delay/3600);
                                    if ($hour_delay < 24){
                                        if ($hour_delay < 2){
                                            echo "$hour_delay hour ago";
                                        } else {
                                            echo "$hour_delay hours ago";
                                        }
                                    } else {
                                        $day_delay = intval($sec_delay/24/3600);
                                        if ($day_delay < 2){
                                            echo "$day_delay day ago";
                                        } else {
                                            echo "$day_delay days ago";
                                        }
                                    }
                                }
                            }
                            echo "</p>";
                        ?>
                        <p> <?php echo $output; ?> </p>
                    <?php
                    }
                    ?>
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

            $("#submit-button").on("click", function(){
                document.getElementById("content").style.opacity = "0.3";
                document.getElementById("img").src = "../photo/load-otp.gif";
            });
        </script>
        <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
        <script src="../vendor/bootstrap/js/bootstrap-select-language.js" type="text/javascript"></script>
        
    </body>
