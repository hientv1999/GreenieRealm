<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
include "../../template/check_alive.php";

// Include config file
require_once "../../classes/Auth.php";
require_once "../../classes/Table.php";
$db_handle = new DBController();
$table = new Table();
$auth = new Auth();

$msg = "";
$result = $db_handle->runBaseQuery("SELECT username FROM users ");
foreach ($result as $row){
    $username = $row["username"];
    if(isset($_GET[$username.'-request'])) {
        $delete_account = $username;
        $_SESSION["delete_account"] = $username;
        header("location: delete_account.php");
    }
}

if ($_SESSION["privilege"] != 2 && $_SESSION["username"] != $_SESSION["delete_account"]){
    header("location: ../home.php");
    exit;
} 
$username = $_SESSION["username"];
$password = "";
$password_err = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    // Validate credentials
    if(empty($password_err)){
        // Prepare a select statement
        $user = $auth->getMemberByUsername($username);
        if (empty($user)){  // username is NOT existed
            $msg = "Who are you?";
        } else {
            if (password_verify($password, $user[0]["password"])) {
                if ($privilege == 2 && $_SESSION["delete_account"] == $_SESSION["username"]){
                    $msg = "You cannot delete your self because you are the admin. You must transfer admin first.";
                } else {
                    $username = $_SESSION["delete_account"];
                    $db_handle->runBaseQuery("DELETE FROM users WHERE username = '$username'");
                    $db_handle->runBaseQuery("DELETE FROM request WHERE username = '$username'");
                    $db_handle->runBaseQuery("DELETE FROM tbl_token_auth WHERE username = '$username'");
                    $db_handle->runBaseQuery("DELETE FROM settings WHERE username = '$username'");
                    if ($_SESSION["delete_account"] == $_SESSION["username"]){
                        header("Refresh:0; url=../logout.php");
                        exit;
                    } else {
                        header("Refresh:0; url=../management.php");
                        exit;
                    }
                } 
            } else {
                $password_err = "Wrong password";
            }
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
        <title><?php echo htmlspecialchars($_SESSION["username"]); ?> - Delete Account</title> 
        <link href="../../photo/favicon.png" rel="icon">
        <link href="../../css/styles.css" rel="stylesheet" />
        <link href="../../css/style.css" type="text/css" rel="stylesheet"><!-- button css -->
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
                <div class="container-fluid" style = "width: 500px; height: 1000px; text-align: left; overflow: hidden; padding-top: 50px">
                    <?php
                    if (empty($msg)){
                        ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                        <?php
                            $delete = $_SESSION["delete_account"];
                            if ($delete == $_SESSION["username"]){
                                echo "<h2 style=\"text-align: center\"> Delete yourself </h2>";
                            } else {
                                echo "<h2 style=\"text-align: center\"> Delete account </h2>";
                                echo "<p style=\"text-align: center; font-size: 24px; opacity: 0.5 \">$delete</p>";
                            }
                            
                        ?>
                            <p style="text-align: center">Please fill out this form with your credentials</p>
                            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" id="pw">
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
                        <?php
                    }
                    ?>
                    <p><?php echo "$msg"; ?> </p>
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