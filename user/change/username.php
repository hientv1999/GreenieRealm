<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
include "../../template/check_alive.php";
 
// Include config file
require_once "../../classes/Table.php";
$db_handle = new DBController();

// Define variables and initialize with empty values
$current_password = $new_username = $confirm_username = "";
$current_password_err = $new_username_err = $confirm_username_err = "";
$current_user = $_SESSION["username"];
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate current password
    if(empty(trim($_POST["current_password"]))){
        $current_password_err = "Please enter the current password.";     
    } else {
        $current_password = trim($_POST["current_password"]);
    }
    // Validate username
    if (empty(trim($_POST["new_username"]))) {
        $new_username_err = "Please enter the new username.";
    } else if (trim($_POST["new_username"]) == $current_user) {
        $new_username_err = "New username cannot be the same as current username";
    } else {
        $new_username = trim($_POST["new_username"]);
        // check if username is unique
        $unique = $db_handle->runBaseQuery("SELECT id FROM users WHERE username = '$new_username' LIMIT 1 ");
        if (count($unique) == 1){
            $new_username_err = "This username was already taken.";
        } 
    }

    // Validate confirm  username
    if(empty(trim($_POST["confirm_username"]))){
        $confirm_username_err = "Please confirm the username.";
    } else{
        $confirm_username = trim($_POST["confirm_username"]);
        if(empty($new_username_err) && ($new_username != $confirm_username)){
            $confirm_username_err = "Username does not match.";
        }
    }
    
    // Check input errors before updating the database
    if(empty($current_password_err) &&  empty($new_username_err) && empty($confirm_username_err)){
        //Validate current password
        $result = $db_handle->runBaseQuery("SELECT password FROM users WHERE username = '$current_user' LIMIT 1");
        if(!password_verify($current_password, $result[0]["password"])){
            $current_password_err = "Wrong current password";
        } else {
            // Prepare an update statement
            $param_id = $_SESSION["id"];
            $db_handle->runBaseQuery("UPDATE users SET username = '$new_username' WHERE id = '$param_id'");
            $db_handle->runBaseQuery("UPDATE tbl_token_auth SET username = '$new_username' WHERE username = '$current_user'");
            $db_handle->runBaseQuery("UPDATE request SET username = '$new_username' WHERE username = '$current_user'");
            $_SESSION["username"] = $new_username;
            header("location: setting.php");
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
        <title><?php echo htmlspecialchars($_SESSION["username"]); ?> - Change Username</title> 
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
                                <h2 style="text-align: center">Change Username</h2>
                                <p style="text-align: center">Please fill out this form to change your username.</p>
                                <p style="text-align: center; color: rgb(230,92,92)">Your username must be unique.</p>
                                <div class="form-group <?php echo (!empty($current_password_err)) ? 'has-error' : ''; ?>">
                                    <label>Current Password</label>
                                    <input type="password" name="current_password" class="form-control" value="<?php echo $current_password; ?>" id="pw">
                                    <span style="font-size: 14px; color: #fc8f8f;"><?php echo $current_password_err; ?></span>
                                </div>
                                <div class="form-group <?php echo (!empty($new_username_err)) ? 'has-error' : ''; ?>">
                                    <label>New Username</label>
                                    <input type="username" name="new_username" class="form-control" value="<?php echo $new_username; ?>">
                                    <span style="font-size: 14px; color: #fc8f8f;"><?php echo $new_username_err; ?></span>
                                </div>
                                <div class="form-group <?php echo (!empty($confirm_username_err)) ? 'has-error' : ''; ?>">
                                    <label>Confirm Username</label>
                                    <input type="username" name="confirm_username" class="form-control">
                                    <span style="font-size: 14px; color: #fc8f8f;"><?php echo $confirm_username_err; ?></span>
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
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
            }
        </script>
        <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
        <script src="../../vendor/bootstrap/js/bootstrap-select-language.js" type="text/javascript"></script>
    </body>
</html>