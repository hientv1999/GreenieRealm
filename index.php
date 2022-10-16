<?php
// Initialize the session
session_start();
require_once "classes/Auth.php";
require_once "classes/Util.php";
require_once "classes/Table.php";
$auth = new Auth();
$db_handle = new DBController();
$util = new Util();
$table = new Table();
// Check if remember me 
require_once "authCookieSessionValidate.php";

// launch preloading screen once
$loading = true;
if (isset($_SESSION["loading"]) && $_SESSION["loading"] === true){
  $loading = false;
} else {
  $_SESSION["loading"] = true;
}
// create TABLE users if not existed
$TABLE_user_sql = "CREATE TABLE users (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
  username VARCHAR(255) NOT NULL UNIQUE, 
  password VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  hash VARCHAR(32) NOT NULL,
  language VARCHAR(2) NOT NULL DEFAULT 'en',
  created_at DATETIME NOT NULL,
  last_login DATETIME DEFAULT NULL,
  active INT(1) NOT NULL DEFAULT 0,
  privilege INT(1) NOT NULL DEFAULT 0,
  PIN INT(6) NOT NULL DEFAULT 000000,
  timezone INT(6),
  date_structure VARCHAR(5) NOT NULL DEFAULT 'd-m-Y'
)";
$msg = $table->createTable("users", $TABLE_user_sql);

// create TABLE request if not existed
$TABLE_request_sql = "CREATE TABLE request (
  username VARCHAR(255) NOT NULL PRIMARY KEY UNIQUE
)";
$msg = $table->createTable("request", $TABLE_request_sql);

// create TABLE tbl_token_auth if not existed
$TABLE_tbl_token_auth_sql = "CREATE TABLE tbl_token_auth (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL UNIQUE,
  password_hash varchar(255) NOT NULL,
  selector_hash varchar(255) NOT NULL,
  is_expired int(11) NOT NULL DEFAULT 0,
  expiry_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$msg = $table->createTable("tbl_token_auth", $TABLE_tbl_token_auth_sql);
// create TABLE setting if not existed
$TABLE_settings_sql = "CREATE TABLE settings (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL UNIQUE,
  graph_division VARCHAR(6) NOT NULL DEFAULT 'minute',
  graph_length INT(2) NOT NULL DEFAULT 8,
  graph_type VARCHAR(6) NOT NULL DEFAULT 'line'
  outdoor_device VARCHAR(32) NULL
)";
$msg = $table->createTable("settings", $TABLE_settings_sql);
// create TABLE otp if not existed
$TABLE_otp_sql = "CREATE TABLE otp (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  OTP INT(4) NOT NULL,
  MAC VARCHAR(17) NOT NULL,
  expiry_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$msg = $table->createTable("otp", $TABLE_otp_sql);
//create TABLE avatar if not existed
$TABLE_avatar_sql = "CREATE TABLE avatars (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  user VARCHAR(255) NOT NULL UNIQUE,
  filename VARCHAR(255)
)";
$msg = $table->createTable("avatars", $TABLE_avatar_sql);
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $verified_err ="";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials and verified account
    if(empty($username_err) && empty($password_err) && empty($email_err) && empty($verified_err)){
      $user = $auth->getMemberByUsername($username);
      if (empty($user)){  // username is NOT existed
        $username_err = "No account found.";
      } else {  // username is existed
        // check password
        if (password_verify($password, $user[0]["password"])) {
          $isAuthenticated = true;
        }
        // if password is correct
        if ($isAuthenticated) {
          if ($user[0]["active"] == 1){ //active account, allow login
            $_SESSION["id"] = $user[0]["id"];
            // Set Auth Cookies if 'Remember Me' checked
            if (! empty($_POST["remember"])) {
              setcookie("member_login", $username, $cookie_expiration_time);
              $random_password = $util->getToken(16);
              setcookie("random_password", $random_password, $cookie_expiration_time);
              
              $random_selector = $util->getToken(32);
              setcookie("random_selector", $random_selector, $cookie_expiration_time);
              
              $random_password_hash = password_hash($random_password, PASSWORD_DEFAULT);
              $random_selector_hash = password_hash($random_selector, PASSWORD_DEFAULT);
              
              $expiry_date = date("Y-m-d H:i:s", $cookie_expiration_time);
              
              // mark existing token as expired
              $userToken = $auth->getTokenByUsername($username);
              if (! empty($userToken[0]["id"])) {
                  $auth->removeExpired($userToken[0]["id"]);
              }
              // Insert new token
              $auth->insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date);
            } else {
                $util->clearAuthCookie();
            }
            // update last login time
            $last_login = gmdate('Y-m-d H:i:s');
            $id = $user[0]["id"];
            $db_handle->runBaseQuery("UPDATE users SET last_login = '$last_login' WHERE id = '$id'");
            // deliver to home page
            session_start();
            // Store data in session variables
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $user[0]["id"];
            $_SESSION["username"] = $user[0]["username"]; 
            $_SESSION["language"] = $user[0]["language"];
            $_SESSION["privilege"] = $user[0]["privilege"];
            $_SESSION["timezone"] = $user[0]["timezone"];
            $_SESSION["date_structure"] = $user[0]["date_structure"];
            $_SESSION["setup_mode"] = "OTP";
            $language = $_SESSION["language"];
            setcookie('googtrans', '/en/'. $language, $cookie_expiration_time);
            $util->redirect("user/home.php");
          } else {
            $verified_err = "Please verify your account first. Check your email for verify link";
          }
        } else {
          $password_err = "Invalid login"; // wrong password
        }
      }
    }
      
      
}
?>

<!DOCTYPE html>
<html lang="en">
<head >
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <title>Greenie Realm - Welcome</title>
  <style>
  img {
  border-radius: 50%;
  opacity: 0.8;
  transition: opacity 0.7s, -webkit-transform 0.7s, -ms-transform 0.7s, transform 0.7s;
  transition-timing-function: ease;
  }

  img:hover {
    opacity: 1;
    -webkit-transform: scale(1.3);
    -ms-transform: scale(1.3);
    transform: scale(1.3);
  }

  p {
    text-align: center;
  }
  </style>
  <!-- Favicons -->
  <link href="photo/favicon.png" rel="icon">

  <!-- Custom styles for this template -->
  <link href="css/full-width-pics.css" rel="stylesheet">
  <!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
  <!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
  <link rel="stylesheet" type="text/css" href="css/styles.css">
  <link href="../../css/style.css" type="text/css" rel="stylesheet"> <!-- button css -->
        
  <link href="vendor/bootstrap/css/bootstrap-mainpage.css" rel="stylesheet" type="text/css" />
  <link href="vendor/bootstrap/css/bootstrap-select-language.css" rel="stylesheet" type="text/css" />
  <link href="css/flag-icon.css" rel="stylesheet" type="text/css" />
  <!-- <link href="css/boxicons/css/boxicons.min.css" rel="stylesheet"> -->
  <link rel="stylesheet" type="text/css" href="css/effect1.css" />
  <script src="vendor/bootstrap/js_preloading/modernizr.custom.js"></script>
  
</head>
<body>
  <!-- initial header -->
  <div id="ip-container" class="ip-container">
    <!-- loader -->
    <?php
    if ($loading){
    ?>
      <header class="ip-header">
        <h1 class="ip-logo">
          <img class="img-fluid d-block mx-auto" src="photo/logo_size.jpg" alt="GreenieRealm" style="width:180px; border-radius: 50%; position: relative; bottom: -60px"> 
          <svg class="ip-inner" width="100%" height="100%" viewBox="0 0 300 160" preserveAspectRatio="xMidYMin meet" aria-labelledby="logo_title">
            
          </svg>
        </h1>
        
        <div class="ip-loader">
          <svg class="ip-inner" width="60px" height="60px" viewBox="0 0 80 80">
            <path class="ip-loader-circlebg" d="M40,10C57.351,10,71,23.649,71,40.5S57.351,71,40.5,71 S10,57.351,10,40.5S23.649,10,40.5,10z"/>
            <path id="ip-loader-circle" class="ip-loader-circle" d="M40,10C57.351,10,71,23.649,71,40.5S57.351,71,40.5,71 S10,57.351,10,40.5S23.649,10,40.5,10z"/>
          </svg>
        </div>     
      </header>
    <?php
      }
    ?>
  </div>
  
  <?php include "template/topbar_index.html" ?>

  <!-- Content section--> 
  <section class="py-5">
    <div class="container"></div>
      <p class="lead" style="font-family: Verdana, Geneva, Tahoma, sans-serif">Control your home by a touch</p>
    </div>
  </section>

  <!-- Login form section-->
  <div class="limiter">
		<div class="container-login100">
			<div  class="wrap-login100 p-l-55 p-r-55 p-t-54 p-b-54">
				<form class="login100-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<span class="login100-form-title p-b-49 ">
						<strong style="font-size: 30px">Login<br></strong>
            <p style="font-family: Verdana, Geneva, Tahoma, sans-serif">Hi, did I see you before?<br></p>
            <span style="font-size: 14px; color: #fc8f8f;" ><?php echo $verified_err; ?></span>
            <span style="font-size: 14px; color: #fc8f8f;" ><?php echo $msg; ?></span>
					</span>

					<div class="wrap-input100  m-b-23 " >
						<span class="label-input100">Username</span>
            <span style="font-size: 14px; color: #fc8f8f;" ><?php echo $username_err; ?></span>
						<input class="input100" type="text" name="username" placeholder="Type your username" value="<?php if (empty($_SESSION['username'])) echo $username; else echo $_SESSION["username"]; ?>" >
						<span class="focus-input100" data-symbol="&#xf206;"></span>
					</div>

					<div class="wrap-input100 m-b-23 " >
						<span class="label-input100">Password</span>
            <span style="font-size: 14px; color: #fc8f8f;" ><?php echo $password_err; ?></span>
            <input class="input100"  type="password" name="password" placeholder="Type your password" value="<?php echo $password; ?>" id="pw">
            <span class="focus-input100" data-symbol="&#xf190;"></span>
					</div>
          <br>
          <div style="text-align: left; position: relative; bottom: 5px; white-space:nowrap; ">
          <label class="checkbox bounce" style="display: inline-block; vertical-align: middle">
            <input type="checkbox" onclick="myFunction()" > 
              <svg viewBox="0 0 21 21">
                <polyline points="5 10.75 8.5 14.25 16 6"></polyline>
              </svg>
          </label>
          <h1 style ="font-size: 15px; display: inline-block; vertical-align: middle">Show password</h1>
          </div>

          <div style="text-align: left; position: relative; bottom: 5px; white-space:nowrap; ">
          <label class="checkbox bounce" style="display: inline-block; vertical-align: middle">
            <input type="checkbox" name="remember" id="remember" <?php if(isset($_COOKIE["member_login"])) { ?> checked <?php } ?> > 
              <svg viewBox="0 0 21 21">
                <polyline points="5 10.75 8.5 14.25 16 6"></polyline>
              </svg>
          </label>
          <h1 style ="font-size: 15px; display: inline-block; vertical-align: middle">Stay logged in</h1>
          </div>

					<div class="text-right p-t-8 p-b-31"  >
						<a href="forget_password.php" style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 15px;">
							Forgot password?
						</a>
					</div>
					<img id="img" style="max-width: 100px; display: none; margin:0 auto; "  src="../photo/load-send.gif" >
					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn" style="width:200px; opacity:70%">
							<div class="login100-form-bgbtn"></div>
              <button id = "submit-button" type="submit" class="login100-form-btn" >Login</button>                                         
						</div>
					</div>

					<div class="flex-col-c p-t-50">
						<a href="register.php" style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 15px;">
              Don't have an account?
						</a>
					</div>

				</form>
			</div>
		</div>
	</div>

  <!-- Footer -->
  <?php include "template/footbar_index.php" ?>

  <script src="vendor/bootstrap/js_preloading/classie.js"></script>
  <script src="vendor/bootstrap/js_preloading/pathLoader.js"></script>
  <script src="vendor/bootstrap/js_preloading/main.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script> <!-- menu, lnaguage toggle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>  <!-- menu, lnaguage toggle -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script> <!-- menu icon on mobile -->      
  <script src="vendor/jquery/scripts.js"></script> <!-- get curren year, highglight current tab -->
  <script src="jquery.js" type="text/javascript"></script>
  <script src="bootstrap-2.js" type="text/javascript"></script>
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
      $("#submit-button").on("click", function(){
        document.getElementById("img").style.display = "block";
        document.getElementById("submit-button").style.display = "none";
      });
      var currentLocation = window.location.protocol + "//" + window.location.hostname;
      document.cookie = "link="+currentLocation;
      //get timezone
      var timezone_offset_minutes = new Date().getTimezoneOffset();
      timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;
      // timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes*60, false);
      document.cookie = "timezone="+ timezone_offset_minutes;
  </script>

  <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
  <script src="vendor/bootstrap/js/bootstrap-select-language.js" type="text/javascript"></script>
</body>
</html>





