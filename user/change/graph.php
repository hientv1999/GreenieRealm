<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
include "../../template/check_alive.php";
 
// Include config file
require_once "../../classes/DBController.php";
$db_handle = new DBController();
// Define variables and initialize with empty values
$graph_type = $graph_division = $graph_length = "";
$graph_type_err = $graph_division_err = $graph_length_err = "";
$current_user = $_SESSION["username"];
$result = $db_handle->runBaseQuery("SELECT graph_division, graph_length, graph_type FROM settings WHERE username = '$current_user'");
$msg = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){     
    if (empty(trim($_POST["graph_division"]))){
        $graph_division_err = "Please select a graph division you prefer.";
        $graph_division = $result[0]["graph_division"];
    } else {
        $graph_division = trim($_POST["graph_division"]);
        if ($result[0]["graph_division"] == $graph_division){
            $graph_division_err = "Please select a graph division that is different from the current one";
        }
    }

    if (empty(trim($_POST["graph_length"]))){
        $graph_length_err = "Please select a graph sample amount you prefer.";
        $graph_length = $result[0]["graph_length"];
    } else {
        $graph_length = trim($_POST["graph_length"]);
        if ($result[0]["graph_length"] == $graph_length){
            $graph_length_err = "Please select a graph sample amount that is different from the current one";
        }
    }

    if(empty(trim($_POST["graph_type"]))){
        $graph_type_err = "Please select a graph type you prefer.";
        $graph_type = $result[0]["graph_type"];
    } else {
        $graph_type = trim($_POST["graph_type"]);
        if ($result[0]["graph_type"] == $graph_type){
            $graph_type_err = "Please select a graph type that is different from the current one";
        }
    }
    
    // Check input errors before updating the database
    if(empty($graph_type_err) || empty($graph_length_err) || empty($graph_division_err)){
        // Prepare an update statement
        $db_handle->runBaseQuery("UPDATE settings SET graph_division = '$graph_division', graph_length = '$graph_length', graph_type = '$graph_type' WHERE username = '$current_user'");
        header("location: setting.php");
        exit();
    } else {
        $msg = "Please change at least 1 setting";
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
        <title><?php echo htmlspecialchars($_SESSION["username"]); ?> - Change Graph</title> 
        <link href="../../photo/favicon.png" rel="icon">
        <link href="../../css/styles.css" rel="stylesheet" />
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
                        <h2 style="text-align: center">Change Graphs</h2>
                        <p style="text-align: center; color: rgb(230,92,92)">Your current graph settings are: <?php echo $result[0]["graph_division"] . ', ' . $result[0]["graph_length"] . ', ' . $result[0]["graph_type"]?></p> 
                            <p style="text-align: center">Please choose a graph setting below</p>
                            <p style="font-size: 14px; color: #fc8f8f; text-align: center;" ><?php echo $msg ?> <br>
                                <br>
                                <select  class="selectpicker" style="align-items: center" data-width="fit" name = "graph_division" >
                                    <option selected disabled hidden style="display:none;">Graph Division</option>
                                    <option value="minute">minute</option>
                                    <option value="hour">hour</option>
                                    <option value="day">day</option>
                                    <option value="month">month</option>
                                    <option value="year">year</option>
                                </select>
                                <select  class="selectpicker" style="align-items: center" data-width="fit" name = "graph_length" >
                                    <option selected disabled hidden style="display:none;">Graph length</option>
                                    <?php
                                        for ($i=4; $i<13; $i++){
                                            echo "<option value=$i>$i</option>";
                                        }
                                    ?>
                                </select>
                                <select  class="selectpicker" style="align-items: center" data-width="fit" name = "graph_type" >
                                    <option selected disabled hidden style="display:none;">Graph type</option>
                                    <option value="line">line</option>
                                    <option value="bar">bar</option>
                                </select>
                            </p>
                            <br>
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
        var currentLocation = window.location.protocol + "//" + window.location.hostname;
        document.cookie = "link="+currentLocation;
    </script>
    <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
    <script src="../../vendor/bootstrap/js/bootstrap-select-language.js" type="text/javascript"></script>
</body>
</html>