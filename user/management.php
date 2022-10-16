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
$username = $email  = $created_at = $last_login  = $active = $privilege = $timezone = $requested_array = [];
$total_user = 0;
$requested = true;

//if timezone is never updated, then do it now
if ($_SESSION["timezone"] != $current_timezone){
    //update timezone
    $db_handle->runBaseQuery("UPDATE users SET timezone = '$current_timezone' WHERE username = '$current_user'");
    $_SESSION["timezone"] = $current_timezone;
}

// load the list of users
$result = $db_handle->runBaseQuery("SELECT username, email, created_at, last_login, active, privilege, timezone FROM users ");
$total_user = count($result);
foreach ($result as $row){
    array_push($username, $row["username"]);
    array_push($email, $row["email"]);
    array_push($created_at, $row["created_at"]);
    array_push($last_login, $row["last_login"]);
    array_push($active, $row["active"]);
    array_push($privilege, $row["privilege"]);
    array_push($timezone, $row["timezone"]);
    $u = $row["username"];
    $result_request = $db_handle->runBaseQuery("SELECT username FROM request WHERE username = '$u'");
    if (empty($result_request)){
        array_push($requested_array, false);
    } else {
        array_push($requested_array, true);
    }
    #update current privilege
    if ($row["username"] == $current_user){
        $current_privilege = $row["privilege"];
        $_SESSION["privilege"] = $row["privilege"];
    }
}

if ($current_privilege !=2 ){
    //check if request for permission has been sent before
    $result_privilege = $db_handle->runBaseQuery("SELECT username FROM request WHERE username = '$current_user'");
    if (empty($result_privilege)){
        $requested = false;
    } else {
        $requested = true;
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
        <style>
        tr, td {
            white-space:nowrap;
        }
        </style>
        <title><?php echo htmlspecialchars($_SESSION["username"]); ?> - <?php echo ($_SESSION["privilege"] == 2) ? 'Management' : 'Accounts' ?></title> 
        <link href="../photo/favicon.png" rel="icon">
        <link href="../css/styles.css" rel="stylesheet"> <!-- css for navbar -->
        <!-- Bootstrap core CSS -->
        <link href="../vendor/bootstrap/css/bootstrap-home.css" rel="stylesheet" type="text/css"> <!-- main font -->
        <link href="../vendor/bootstrap/css/bootstrap-select-language.css" rel="stylesheet" type="text/css"> <!-- language dropout menu -->
         <link href="../css/flag-icon.css" rel="stylesheet" type="text/css"> <!--country flags -->
        <!-- <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous"> -->
        
    </head>
    <body class="sb-nav-fixed">
        <?php include "../template/topbar.html" ?>
        <div id="layoutSidenav">
            <?php include "../template/navbar.html" ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <br>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                <?php echo $name_tab ?>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table  style="text-align: center" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        
                                        <?php
                                        if ($current_privilege == 2){
                                            echo "<thead>";
                                                echo"<tr>";
                                                    echo"<th width = \"15%\">Username</th>";
                                                    echo"<th width = \"20%\">Email</th>";
                                                    echo"<th width = \"15%\">Time created</th>";
                                                    echo"<th width = \"15%\">Last login</th>";
                                                    echo"<th width = \"10%\">Activated</th>";
                                                    echo"<th width = \"8%\">Timezone</th>";
                                                    echo"<th width = \"7%\">Role</th>";
                                                    echo"<th width = \"15%\">Action</th>";
                                                echo"</tr>";
                                            echo "</thead>";

                                            echo"<tfoot>";
                                                echo"<tr>";
                                                    echo"<th>Username</th>";
                                                    echo"<th>Email</th>";
                                                    echo"<th>Time created</th>";
                                                    echo"<th>Last login</th>";
                                                    echo"<th>Activated</th>";
                                                    echo"<th>Timezone</th>";
                                                    echo"<th>Role</th>";
                                                    echo"<th>Action</th>";
                                                echo"</tr>";
                                            echo"</tfoot>";

                                            echo"<tbody>";
                                            for ($x = 0; $x < $total_user; $x++) {
                                                $opacity = 1;
                                                if ($active[$x] == 0){
                                                    $opacity = 0.65;
                                                }
                                                echo "<tr>";
                                                    echo "<td style=\"vertical-align: middle; opacity: $opacity\">$username[$x]</td>";
                                                    echo "<td style=\"vertical-align: middle; opacity: $opacity\">$email[$x]</td>";
                                                    if ($current_timezone != 0){
                                                        $created_at[$x] = date($current_date_structure . " H:i:s", strtotime("{$current_timezone} minutes", strtotime($created_at[$x])));
                                                        if ($last_login[$x] != NULL){
                                                            $last_login[$x] = date($current_date_structure . " H:i:s", strtotime("{$current_timezone} minutes", strtotime($last_login[$x])));
                                                        } else {
                                                            $last_login[$x] = 'Never logged in';
                                                        }
                                                        
                                                    }
                                                    echo "<td style=\"vertical-align: middle; opacity: $opacity\">$created_at[$x]</td>";
                                                    echo "<td style=\"vertical-align: middle; opacity: $opacity\">$last_login[$x]</td>";
                                                    if ($active[$x] == 1){
                                                        echo "<td style=\"vertical-align: middle; opacity: $opacity\">Yes</td>";
                                                    } else {
                                                        echo "<td style=\"vertical-align: middle; opacity: $opacity\">No</td>";
                                                    }
                                                    if ($timezone[$x] == NULL){
                                                        echo "<td style=\"vertical-align: middle; opacity: $opacity\">GMT --:--</td>";
                                                    } else {
                                                        $minute = $timezone[$x] % 60;
                                                        $hour = ($timezone[$x] - $minute)/60;
                                                        if ($minute == 0){
                                                            echo "<td style=\"vertical-align: middle; opacity: $opacity\">GMT $hour:00</td>";
                                                        } else {
                                                            echo "<td style=\"vertical-align: middle; opacity: $opacity\">GMT $hour:$minute</td>";
                                                        }
                                                    }
                                                    
                                                    
                                                    if ($privilege[$x] == 0){
                                                        echo "<td style=\"vertical-align: middle; opacity: $opacity\">Guest</td>";
                                                        ?>
                                                        <form action = "update_content.php" method = "get" >
                                                        <?php
                                                        echo "<td style=\"vertical-align: middle;\">";
                                                        if ($active [$x] != 0){
                                                            if ($requested_array[$x]){
                                                                echo "<input type=\"submit\" style=\"margin-bottom: 5px; box-shadow: 
                                                                0 0 5px #007BFF,
                                                                0 0 10px rgb(0 123 255 / 0.8),
                                                                0 0 15px rgb(0 123 255 / 0.7),
                                                                0 0 20px rgb(0 123 255 / 0.6); \" class=\"btn btn-primary\" name=\"$username[$x]-grant\" value=\"Grant Privilege\">";
                                                            } else {
                                                                echo "<input type=\"submit\" style=\"margin-bottom: 5px\" class=\"btn btn-primary\"  name=\"$username[$x]-grant\" value=\"Grant Privilege\">";
                                                            }
                                                            
                                                        }
                                                        ?>
                                                        </form>
                                                        <form action = "change/delete_account.php" method = "get" >
                                                        <?php
                                                        echo "<input type=\"submit\" style=\"margin-top: 5px\" class=\"btn btn-primary\" name=\"$username[$x]-request\" value=\"Delete Account\"></td>";
                                                        ?>
                                                        </form>
                                                        <?php
                                                    } else if ($privilege[$x] == 1){
                                                        echo "<td style=\"vertical-align: middle; opacity: $opacity\">Member</td>";
                                                        ?>
                                                        <form action = "update_content.php" method = "get" >
                                                        <?php
                                                        echo "<td style=\"vertical-align: middle; opacity: $opacity\"><input type=\"submit\" style=\"margin-bottom: 5px\" class=\"btn btn-primary\" name=\"$username[$x]-remove\" value=\"Remove Privilege\">";
                                                        ?>
                                                        </form>
                                                        <form action = "change/delete_account.php" method = "get" >
                                                        <?php
                                                        echo "<input type=\"submit\" style=\"margin-top: 5px;\" class=\"btn btn-primary\" name=\"$username[$x]-request\" value=\"Delete Account\"></td>";
                                                        ?>
                                                        </form>
                                                        <?php
                                                    } else {
                                                        echo "<td style=\"vertical-align: middle; opacity: $opacity\">Admin</td>";
                                                        echo "<td style=\"vertical-align: middle; opacity: $opacity\"></td>";
                                                    }      
                                                    
                                                echo "</tr>";
                                            }
                                            echo"</tbody>";
                                        } else if ($current_privilege == 1){
                                            echo "<thead>";
                                                echo"<tr>";
                                                    echo"<th width = \"15%\">Username</th>";
                                                    echo"<th width = \"20%\">Email</th>";
                                                    echo"<th width = \"15%\">Time created</th>";
                                                    echo"<th width = \"15%\">Last login</th>";
                                                    echo"<th width = \"10%\">Activated</th>";
                                                    echo"<th width = \"15%\">Timezone</th>";
                                                    echo"<th width = \"10%\">Role</th>";
                                                echo"</tr>";
                                            echo "</thead>";

                                            echo"<tfoot>";
                                                echo"<tr>";
                                                    echo"<th>Username</th>";
                                                    echo"<th>Email</th>";
                                                    echo"<th>Time created</th>";
                                                    echo"<th>Last login</th>";
                                                    echo"<th>Activated</th>";
                                                    echo"<th>Timezone</th>";
                                                    echo"<th>Role</th>";
                                                echo"</tr>";
                                            echo"</tfoot>";

                                            echo"<tbody>";
                                            for ($x = 0; $x < $total_user; $x++) {
                                                $opacity = 1;
                                                if ($active[$x] == 0){
                                                    $opacity = 0.65;
                                                }
                                                echo "<tr>";
                                                    echo "<td style=\"vertical-align: middle; opacity: $opacity\">$username[$x]</td>";
                                                    echo "<td style=\"vertical-align: middle; opacity: $opacity\">$email[$x]</td>";
                                                    if ($current_timezone != 0){
                                                        $created_at[$x] = date($current_date_structure . " H:i:s", strtotime("{$current_timezone} minutes", strtotime($created_at[$x])));
                                                        if ($last_login[$x] != NULL){
                                                            $last_login[$x] = date($current_date_structure . " H:i:s", strtotime("{$current_timezone} minutes", strtotime($last_login[$x])));
                                                        } else {
                                                            $last_login[$x] = 'Never logged in';
                                                        }
                                                    }
                                                    echo "<td style=\"vertical-align: middle; opacity: $opacity\">$created_at[$x]</td>";
                                                    echo "<td style=\"vertical-align: middle; opacity: $opacity\">$last_login[$x]</td>";
                                                    if ($active[$x] == 1){
                                                        echo "<td style=\"vertical-align: middle; opacity: $opacity\">Yes</td>";
                                                    } else {
                                                        echo "<td style=\"vertical-align: middle; opacity: $opacity\">No</td>";
                                                    }
                                                    if ($timezone[$x]==NULL){
                                                        echo "<td style=\"vertical-align: middle; opacity: $opacity\">GMT --:--</td>";
                                                    } else {
                                                        $minute = $timezone[$x] % 60;
                                                        $hour = ($timezone[$x] - $minute)/60;
                                                        if ($minute == 0){
                                                            echo "<td style=\"vertical-align: middle; opacity: $opacity\">GMT $hour:00</td>";
                                                        } else {
                                                            echo "<td style=\"vertical-align: middle; opacity: $opacity\">GMT $hour:$minute</td>";
                                                        }
                                                    }
                                                    

                                                    if ($privilege[$x] == 0){
                                                        echo "<td style=\"vertical-align: middle; opacity: $opacity\">Guest</td>";
                                                    } else if ($privilege[$x] == 1){
                                                        echo "<td style=\"vertical-align: middle; opacity: $opacity\">Member</td>";
                                                    } else {
                                                        echo "<td style=\"vertical-align: middle; opacity: $opacity\">Admin</td>";
                                                    }                                                     
                                                echo "</tr>";
                                            }
                                            echo"</tbody>";

                                        } else {
                                            echo "<thead>";
                                                echo"<tr>";
                                                    echo"<th width = \"30%\">Username</th>";
                                                    echo"<th width = \"15%\">Activated</th>";
                                                    echo"<th width = \"20%\">Timezone</th>";
                                                    echo"<th width = \"15%\">Role</th>";
                                                    echo"<th width = \"20%\">Action</th>";
                                                echo"</tr>";
                                            echo "</thead>";

                                            echo"<tfoot>";
                                                echo"<tr>";
                                                    echo"<th>Username</th>";
                                                    echo"<th>Activated</th>";
                                                    echo"<th>Timezone</th>";
                                                    echo"<th>Role</th>";
                                                    echo"<th>Action</th>";
                                                echo"</tr>";
                                            echo"</tfoot>";

                                            echo"<tbody>";
                                            for ($x = 0; $x < $total_user; $x++) {
                                                $opacity = 1;
                                                if ($active[$x] == 0){
                                                    $opacity = 0.65;
                                                }
                                                echo "<tr>";
                                                    echo "<td style=\"vertical-align: middle; opacity: $opacity\">$username[$x]</td>";
                                                    if ($active[$x] == 1){
                                                        echo "<td style=\"vertical-align: middle; opacity: $opacity \">Yes</td>";
                                                    } else {
                                                        echo "<td style=\"vertical-align: middle; opacity: $opacity\">No</td>";
                                                    }
                                                    if ($timezone[$x] == NULL){
                                                        echo "<td style=\"vertical-align: middle; opacity: $opacity\">GMT --:--</td>";
                                                    } else {
                                                        $minute = $timezone[$x] % 60;
                                                        $hour = ($timezone[$x] - $minute)/60;
                                                        if ($minute == 0){
                                                            echo "<td style=\"vertical-align: middle; opacity: $opacity\">GMT $hour:00</td>";
                                                        } else {
                                                            echo "<td style=\"vertical-align: middle; opacity: $opacity\">GMT $hour:$minute</td>";
                                                        }
                                                    }
                                                    
                                                    if ($privilege[$x] == 2){
                                                        
                                                        echo "<td style=\"vertical-align: middle\">Admin</td>";
                                                        ?>
                                                        <form action = "request_privilege.php" method = "get" >
                                                        <?php
                                                        if ($requested){
                                                            echo "<td style=\"vertical-align: middle\"><input type=\"submit\" class=\"btn btn-primary\" name=\"$current_user-remove\" value=\"Remove Request\"></td>";
                                                        } else {
                                                            echo "<td style=\"vertical-align: middle\"><input type=\"submit\" class=\"btn btn-primary\" name=\"$current_user-request\" value=\"Request Privilege\"></td>";
                                                        }
                                                        
                                                        ?>
                                                        </form>
                                                        <?php
                                                    } else if ($privilege[$x] == 1) {
                                                        echo "<td style=\"vertical-align: middle\">Member</td>";
                                                        echo "<td style=\"vertical-align: middle\"></td>";
                                                    } else {
                                                        echo "<td style=\"vertical-align: middle; opacity: $opacity\">Guest</td>";
                                                        echo "<td style=\"vertical-align: middle\"></td>";
                                                    }                                                   
                                                echo "</tr>";
                                            }
                                            echo"</tbody>";
                                        } 
                                        ?>
                                        
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <?php include "../template/footbar.php" ?>
            </div>
        </div>
        
        <script src = "../vendor/jquery/user_data.js"></script> <!-- get users' weather, location -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script> <!-- menu, lnaguage toggle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>  <!-- menu, lnaguage toggle -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script> <!-- menu icon on mobile -->
        <script src="../vendor/jquery/scripts.js"></script> <!-- get curren year, highglight current tab -->
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
        <script src="../vendor/bootstrap/js/bootstrap-select-language.js" type="text/javascript"></script>
        
    </body>
</html>
