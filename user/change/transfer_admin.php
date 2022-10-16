<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
include "../../template/check_alive.php";

// Check if the user has admin privilege, if not then redirect him to home page
include "../../template/check_privilege.php";
// Include config file
require_once "../../classes/Table.php";
$db_handle = new DBController();

$current_user = $_SESSION["username"];
$current_privilege = $_SESSION["privilege"];
$current_timezone = $_SESSION["timezone"];
$password = $confirm_password = $transfer = "";
$password_err = $confirm_password_err = $transfer_err = "";
$transfer_list = [];
$msg = "";
// make the transfer list

$result = $db_handle->runBaseQuery("SELECT username FROM users WHERE privilege = 1 ");
foreach ($result as $row){
    array_push($transfer_list, $row["username"]);
}


if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate current password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter the admin password";     
    } else {
        $password = trim($_POST["password"]);
    }
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    // Validate username
    if(empty(trim($_POST["transferOption"]))){
        $transfer_err = "Please select a user to transfer to.";
    } else {
        $transfer = trim($_POST["transferOption"]);
    }

    // Check input errors before updating the database
    if(empty($password_err) && empty($confirm_password_err) && empty($transfer_err)){
        //check password
        $result_password = $db_handle->runBaseQuery("SELECT password FROM users WHERE username = '$current_user' LIMIT 1");
        if(!password_verify($password, $result_password[0]["password"])){
            $password_err = "Wrong current password";
        } else {
            // add privilege of new user
            $db_handle->runBaseQuery("UPDATE users SET privilege = 2 WHERE username = '$transfer'");
            $db_handle->runBaseQuery("DELETE FROM request WHERE username = '$transfer'");
            $db_handle->runBaseQuery("UPDATE users SET privilege = 1 WHERE username = '$current_user'");
            $_SESSION["privilege"] = 1;
            ?>
            <script>
                alert("Thanks for being the admin during the past time. You are a member from now");
                window.location.href = "../home.php"
            </script>
            <?php
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
        <title><?php echo htmlspecialchars($_SESSION["username"]); ?> - Transfer Admin</title> 
        <link href="../../photo/favicon.png" rel="icon">
        <link href="../../css/styles.css" rel="stylesheet" />
        <link href="../../css/style.css" type="text/css" rel="stylesheet"> <!-- button css -->
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
                        <div class="col-lg-5">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                                <br>
                                <h2 style="text-align: center">Transfer Admin</h2>
                                <p style="text-align: center">Please select a member below to transfer privilege.</p>
                                <p style="text-align: center; color: rgb(230,92,92)">There can be only 1 admin at any time.</p>
                                <span style="font-size: 14px; color: #fc8f8f;" ><?php echo $msg; ?></span>

                                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                                    <label>Admin Password</label>
                                    <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" id ="pw">
                                    <span style="font-size: 14px; color: #fc8f8f;"><?php echo $password_err; ?></span>
                                </div>
                                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                                    <label>Confirm Admin Password</label>
                                    <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" id = "pw2">
                                    <span style="font-size: 14px; color: #fc8f8f;"><?php echo $confirm_password_err; ?></span>
                                </div>
                                <p style="font-size: 14px; color: #fc8f8f; text-align: center;" ><?php echo $transfer_err ?>
                                <br><br>
                                    <select  class="selectpicker"  data-width="fit" name = "transferOption" >
                                    <option selected disabled hidden style="display:none;">Transfer To</option>
                                    <?php
                                    foreach($transfer_list as $element){
                                        echo "<option value=$element>$element</option>";
                                    }
                                    ?>
                                    </select>
                                </p>
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
                if (x.type === "password" ) {
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