<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
include "../../template/check_alive.php";
 
// Include config file
require_once "../../classes/Table.php";
$db_handle = new DBController();

// Define variables and initialize with empty values
$current_user = $_SESSION["username"];
$timezone = 0;
$timezone_err = "";
$old_timezone = "";
$minute = $_SESSION["timezone"] % 60;
$hour = ($_SESSION["timezone"] - $minute)/60;
if ($minute == 0){
    $old_timezone = strval($hour) . ":" . "00";
} else {
    $old_timezone = strval($hour) . ":" . strval($minute);
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){     
    if(empty(trim($_POST["timezone"]))){
        $timezone_err = "Please select a timezone you prefer.";
    } else {
        $gmt = trim($_POST["timezone"]);
        $offset = intval(substr($gmt, 1, 3))*60 + intval(substr($gmt, 4, 6));
        $timezone = $offset;
        if ($gmt[0] == '-'){
            $timezone = $timezone * (-1);
        }
        if ($_SESSION["timezone"] == $timezone){
            $timezone_err = "Please select a timezone that is different from the current one";
        }

    }
    // Check input errors before updating the database
    if(empty($timezone_err)){
        // Prepare an update statement
        $param_id = $_SESSION["id"];
        $db_handle->runBaseQuery("UPDATE users SET timezone = '$timezone' WHERE id = '$param_id'");          
        $_SESSION["timezone"] = $timezone;
        header("location: setting.php");
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
        <title><?php echo htmlspecialchars($_SESSION["username"]); ?> - Change Timezone</title> 
        <link href="../../photo/favicon.png" rel="icon">
        <link href="../../css/styles.css" rel="stylesheet" />
        <!-- Bootstrap core CSS -->
        <link href="../../vendor/bootstrap/css/bootstrap-mainpage.css" rel="stylesheet">
        <link href="../../vendor/bootstrap/css/bootstrap-select-language.css" rel="stylesheet" type="text/css" />
        <link href="../../css/flag-icon.css" rel="stylesheet" type="text/css" />
        <!-- <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" /> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    
</head>
<body class="sb-nav-fixed">
    <?php include "../../template/topbar_setting.html" ?>
        <div id="layoutSidenav">
            <?php include "../../template/navbar_setting.html" ?>
            <div id="layoutSidenav_content">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">     
                                <br>
                                <h2 style="text-align: center">Change Timezone</h2>
                                <p style="text-align: center">Please select your timezone from the dropdown menu below.</p>
                                <p style="text-align: center; color: rgb(230,92,92)">Your current timezone is GMT <?php echo $old_timezone?></p>
                                <p style="font-size: 14px; color: #fc8f8f; text-align: center;" ><?php echo $timezone_err ?>
                                <br>
                                <select  class="selectpicker" style="align-items: center"   name = "timezone" >
                                    <option selected disabled hidden style="display:none;">Timezone</option>
                                    <option value="-12:00">(GMT -12:00) Eniwetok, Kwajalein</option>
                                    <option value="-11:00">(GMT -11:00) Midway Island, Samoa</option>
                                    <option value="-10:00">(GMT -10:00) Hawaii</option>
                                    <option value="-09:50">(GMT -9:30) Taiohae</option>
                                    <option value="-09:00">(GMT -9:00) Alaska</option>
                                    <option value="-08:00">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
                                    <option value="-07:00">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
                                    <option value="-06:00">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
                                    <option value="-05:00">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
                                    <option value="-04:50">(GMT -4:30) Caracas</option>
                                    <option value="-04:00">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
                                    <option value="-03:50">(GMT -3:30) Newfoundland</option>
                                    <option value="-03:00">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
                                    <option value="-02:00">(GMT -2:00) Mid-Atlantic</option>
                                    <option value="-01:00">(GMT -1:00) Azores, Cape Verde Islands</option>
                                    <option value="+00:00">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
                                    <option value="+01:00">(GMT +1:00) Brussels, Copenhagen, Madrid, Paris</option>
                                    <option value="+02:00">(GMT +2:00) Kaliningrad, South Africa</option>
                                    <option value="+03:00">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
                                    <option value="+03:50">(GMT +3:30) Tehran</option>
                                    <option value="+04:00">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
                                    <option value="+04:50">(GMT +4:30) Kabul</option>
                                    <option value="+05:00">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
                                    <option value="+05:50">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
                                    <option value="+05:75">(GMT +5:45) Kathmandu, Pokhara</option>
                                    <option value="+06:00">(GMT +6:00) Almaty, Dhaka, Colombo</option>
                                    <option value="+06:50">(GMT +6:30) Yangon, Mandalay</option>
                                    <option value="+07:00">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
                                    <option value="+08:00">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
                                    <option value="+08:75">(GMT +8:45) Eucla</option>
                                    <option value="+09:00">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
                                    <option value="+09:50">(GMT +9:30) Adelaide, Darwin</option>
                                    <option value="+10:00">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
                                    <option value="+10:50">(GMT +10:30) Lord Howe Island</option>
                                    <option value="+11:00">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
                                    <option value="+11:50">(GMT +11:30) Norfolk Island</option>
                                    <option value="+12:00">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
                                    <option value="+12:75">(GMT +12:45) Chatham Islands</option>
                                    <option value="+13:00">(GMT +13:00) Apia, Nukualofa</option>
                                    <option value="+14:00">(GMT +14:00) Line Islands, Tokelau</option>
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
        </script>
        <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
        <script src="../../vendor/bootstrap/js/bootstrap-select-language.js" type="text/javascript"></script>
    </body>
</html>