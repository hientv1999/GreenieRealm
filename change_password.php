<?php
// Include config file
require_once "classes/Util.php";
require_once "classes/Table.php";
$db_handle = new DBController();
$util = new Util();
$table = new Table();
// Check if remember me 
require_once "authCookieSessionValidate.php";

$msg = '';
$username = $password  = "";
$old_password = "";
$hash = ""; 
$get_link = "";     
$valid = "glub glub glub";
if(isset($_GET['username']) && !empty($_GET['username']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
  // Verify data
  $username = trim($_GET['username']); // Set username variable
  $_SESSION["username"] = $username;
  $hash = trim($_GET['hash']); // Set hash variable               
  $get_link = "?username=" . $username . "&hash=" . $hash;  
  $sql = "SELECT password FROM users WHERE username=? AND hash=? "; 
  $result = $db_handle->runQuery($sql, "ss", array($username, $hash));
  if (count($result) == 1){
    $old_password = $result[0]["password"];
  }else {
    $valid = 'The link is either invalid or you already have changed your password.';
  }
} else {
  // Invalid approach
  $msg = 'Invalid approach, please use the link that has been send to your email.';
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
  // Validate username
  if(empty(trim($_POST["username"]))){
    $username_err = "Please enter a username.";
} else{
    $username = trim($_POST["username"]);
}
  // Validate password
  if(empty(trim($_POST["password"]))){
    $password_err = "Please enter a password.";     
  } elseif(strlen(trim($_POST["password"])) < 6){
      $password_err = "Password too short";
  } else{
      $password = trim($_POST["password"]);
  }

  // Validate confirm password
  if(empty(trim($_POST["confirm_password"]))){
      $confirm_password_err = "Please confirm password.";     
  } else{
      $confirm_password = trim($_POST["confirm_password"]);
      if(empty($password_err) && ($password != $confirm_password)){
        $confirm_password_err = "Not match.";
      }
  }

  // Check input errors before inserting in database
  if(empty($password_err) && empty($confirm_password_err)){
    if (password_verify($password, $old_password)){
      $msg = "Please use a different password"; 
    } else {
      // Prepare an insert statement
      $sql = "UPDATE users SET password = ?, hash=? WHERE username = ?";
      $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
      $param_hash = md5( mt_rand(0,1000));
      $param_username = $username;
      $db_handle->update($sql, "sss", array($param_password, $param_hash, $param_username));
      #disable remember me on other devices using the same login id
      $db_handle->runBaseQuery("DELETE FROM tbl_token_auth WHERE username = '$username'");
      ?>
        <script>
          alert('Password has been change. Please login again');
          window.location.href = "index.php";
        </script>
      <?php
    }    
  }
}
// Close connection
mysqli_close($link);
?>
 

<!DOCTYPE html>
<html lang="en">
<head >
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
  <title>Verify PIN</title>

  <!-- Favicons -->
  <link href="photo/favicon.png" rel="icon">
  <!-- Bootstrap core CSS -->

  <!-- Custom styles for this template -->
  <link href="css/full-width-pics.css" rel="stylesheet">
  <!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
  <!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
  <link rel="stylesheet" type="text/css" href="css/styles.css">
  <link href="../../css/style.css" type="text/css" rel="stylesheet"> <!-- button css -->
  <link href="vendor/bootstrap/css/bootstrap-mainpage.css" rel="stylesheet">
  <link href="vendor/bootstrap/css/bootstrap-select-language.css" rel="stylesheet" type="text/css" />
  <link href="css/flag-icon.css" rel="stylesheet" type="text/css" />
  <!-- <link href="css/boxicons/css/boxicons.min.css" rel="stylesheet"> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>

  
</head>
<body>
  <!-- Navigation -->
  <?php include "template/topbar_index.html" ?>

  <!-- Content section--> 
  <section class="py-5">
    <div class="container">
      <p class="lead" style="font-family: Verdana, Geneva, Tahoma, sans-serif"><?php echo $valid; ?></p>
    </div>
  </section>

  <?php
  if ($valid =="glub glub glub"){
    ?>
  <!-- Login form section-->
  <div class="limiter">
    <div class="container-login100">
      <div class="wrap-login100 p-l-55 p-r-55 p-t-54 p-b-54">
        <form class="login100-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . $get_link )  ; ?>" method="post">
          <span class="login100-form-title p-b-49 ">
            <strong style="font-size: 30px">Password Change</strong>
            <p style="font-family: Verdana, Geneva, Tahoma, sans-serif">Please fill this form to change your password.<br></p>
            <span style="font-size: 14px; color: #fc8f8f;" ><?php echo  $msg ; ?></span>
          </span>
          <div class="wrap-input100  m-b-23 <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>" >
            <span class="label-input100">Username</span>
            <span style="font-size: 14px; color: #fc8f8f;" ><?php echo $username_err; ?></span>
            <input class="input100" type="text" name="username" value="<?php echo $username; ?>">
            <span class="focus-input100" data-symbol="&#xf206;"></span>
          </div>

          <div class="wrap-input100 m-b-23<?php echo (!empty($password_err)) ? 'has-error' : ''; ?>" >
            <span class="label-input100">New Password</span>
            <span style="font-size: 14px; color: #fc8f8f;" ><?php echo $password_err; ?></span>
            <input class="input100" type="password" name="password" placeholder="Type your new password" value="<?php echo $password; ?>" id="pw">
            <span class="focus-input100" data-symbol="&#xf190;"></span>
          </div>

          <div class="wrap-input100 m-b-23 <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>" >
            <span class="label-input100">Confirm password</span>
            <span style="font-size: 14px; color: #fc8f8f;" ><?php echo $confirm_password_err; ?></span>
            <input class="input100" type="password" name="confirm_password" placeholder="Retype your new password" value="<?php echo $confirm_password; ?>" id="pw2">
            <span class="focus-input100" data-symbol="&#xf190;"></span>
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
          
          <div class="text-right p-t-8 p-b-31"  >
            <a href="index.php" style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 15px;">
              <br>Remembered your password already?
            </a>
          </div>
          
          <div class="container-login100-form-btn">
            <div class="wrap-login100-form-btn">
              <div class="login100-form-bgbtn"></div>
                <button type="submit" class="login100-form-btn" >Submit</button>                                         
            </div>
          </div>

          <div class="flex-col-c p-t-50">
            <a href="register.php" class="txt2">
              Sign up?
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php
  }
  ?>

  <!-- Footer -->
  <?php include "template/footbar_index.php" ?>
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
        var y = document.getElementById("pw2");
        if (x.type === "password" && y.type === "password") {
          x.type = "text";
          y.type = "text";
        } else {
          x.type = "password";
          y.type = "password";
        }
      }
  </script>     
  <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
  <script src="vendor/bootstrap/js/bootstrap-select-language.js" type="text/javascript"></script>
</body>
</html>
