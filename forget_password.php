<?php
session_start();
// Include config file
require_once "classes/Util.php";
require_once "classes/Table.php";
$db_handle = new DBController();
$util = new Util();
$table = new Table();
// Check if remember me 
require_once "authCookieSessionValidate.php";
 
// Define variables and initialize with empty values
$email = $username = "";
$username_err  = $email_err = "";
$msg = "";
$list_username = "";
$num = 0;
$user_name  = [];



// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
  if (empty(trim($_POST["emailOption"]))){
    // Validate username
    if(empty(trim($_POST["email"]))){
      $email_err = "Please enter an email.";
    } else{
      $email = trim($_POST["email"]);
      // check if e-mail address is well-formed
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format";
      } else {
        // Prepare a select statement
        $sql = "SELECT username, language FROM users WHERE email = ?";
        $_SESSION["email"] = $email;
        $result = $db_handle->runQuery($sql, "s", array($email));
        $num = count($result);
        if ($num == 0){
          $msg = "This email is not registered.";
        } else if ($num == 1) {
          $hash = md5( mt_rand(0,1000));
          $username = $result[0]["username"];
          $_SESSION["username"] = $username;
          $db_handle->update("UPDATE users SET hash = ? WHERE username = ?", "ss", array($hash, $username));
          // Get user's browser language
          $language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2); 
          $href = $_COOKIE["link"];
          $output = shell_exec("python3 change_password_mailing.py $username $email $hash $language $href");
          if ($output != NULL){
            ?>
            <script>alert("<?php echo $output?>");</script>
            <?php
          } else {
            $direct_to = explode("@", $email)[1];
            ?>
            <script>
              alert('A link to change password has been sent to your email. Following the instruction to recover your account!');
              window.location.href = "https://<?php echo $direct_to; ?>";
            </script>
            <?php
          }
        } else {
          foreach ($result as $user){
            array_push($user_name, $user["username"]);
          }
          $list_username = "$num usernames linked to this email.";
        }
      }
    }
  } else {
    $hash = md5( mt_rand(0,1000));
    $username = trim($_POST["emailOption"]);
    $_SESSION["username"] = $username;
    $db_handle->update("UPDATE users SET hash = ? WHERE username = ?", "ss", array($hash, $username));
    $emailSendTo = $_SESSION["email"];
    // Get user's browser language
    $language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2); 
    $href = $_COOKIE["link"];
    $output = shell_exec("python3 change_password_mailing.py $username $emailSendTo $hash $language $href");
		if ($output != NULL){
      dump($output);
    }
    ?>
    <script>
      alert('A link to change password has been sent to your email. Following the instruction to recover your account!');
      window.location.href = "https://gmail.com";
    </script>
    <?php
  }   
  // Close connection
  mysqli_close($link);
}
?>
 

<!DOCTYPE html>
<html lang="en">
<head >
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
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
  <title>Greenie Realm - Forget Password</title>

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
        
  <link href="vendor/bootstrap/css/bootstrap-mainpage.css" rel="stylesheet">
  <link href="vendor/bootstrap/css/bootstrap-select-language.css" rel="stylesheet" type="text/css" />
  <link href="css/flag-icon.css" rel="stylesheet" type="text/css" />
  <!-- <link href="css/boxicons/css/boxicons.min.css" rel="stylesheet"> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
  
</head>
<body class="sb-nav-fixed">
  <!-- Navigation -->
  <?php include "template/topbar_index.html" ?>
    
 
  <!-- Login form section-->
  <div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 p-l-55 p-r-55 p-t-54 p-b-54">
        <?php
        if ($num == 0) {
        ?>
				<form class="login100-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<span class="login100-form-title p-b-49 ">
            <strong style="font-size: 30px">Retrieve Password</strong>
            <p style="font-family: Verdana, Geneva, Tahoma, sans-serif">Please fill this form to retrieve your password.<br></p>
            <span style="font-size: 14px; color: #fc8f8f;" ><?php echo $msg; ?></span>
					</span>



          <div class="wrap-input100 <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>" >
					  <span class="label-input100">Email</span>
              <span style="font-size: 14px; color: #fc8f8f;" ><?php echo $email_err; ?></span>
						    <input class="input100" type="email" name="email" placeholder="Type your email" value="<?php echo $email; ?>">
						<span class="focus-input100" data-symbol="&#x2709;"></span>
					</div>

					<div class="text-right p-t-8 p-b-31"  >
						<a href="index.php" style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 15px;">
							<br>Remembered your password already?
						</a>
					</div>
					<img id="img" style="max-width: 100px; display: block; margin:0 auto; "  src="../photo/load-send.gif" >
					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn" style="width:200px; opacity:70%">
							<div class="login100-form-bgbtn"></div>
                <button id = "submit-button" type="submit" class="login100-form-btn" >Retrieve</button>                                         
						</div>
					</div>

					<div class="flex-col-c p-t-50">
						<a href="register.php" style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 15px;">
                Sign up?
						</a>
					</div>
        </form>
        <?php
        } else {
          ?>
          <form class="login100-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <span class="login100-form-title p-b-49 ">
            <strong style="font-size: 30px">Retrieve Password</strong>
            <p style="font-family: Verdana, Geneva, Tahoma, sans-serif">Please fill this form to retrieve your password.<br></p>
            <span style="font-size: 14px; color: #fc8f8f;" ><?php echo $msg; ?></span>
					</span>



          <div class="wrap-input100 <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>" >
					  <span class="label-input100">Email</span>
              <span style="font-size: 14px; color: #fc8f8f;" ><?php echo $email_err; ?></span>
						    <input class="input100" type="email" name="email" placeholder="Type your email" value="<?php echo $email; ?>">
						<span class="focus-input100" data-symbol="&#x2709;"></span>
					</div>
          <br>
          <p style="font-family: Verdana, Geneva, Tahoma, sans-serif"><?php echo $list_username; ?><br></p>
          <p style="font-size: 14px; color: #fc8f8f;" ><?php echo $username_err ?></p>
          <div style = "text-align: center">
          <select  class="selectpicker" data-width ="fit" name = "emailOption" >
            <option selected disabled hidden style="display:none;">Username</option>
            <?php
            foreach($user_name as $element){
              echo "<option value=$element>$element</option>";
            }
            ?>
          </select>
          </div>
          <div class="text-right p-t-8 p-b-31"  >
						<a href="index.php" style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 15px;">
							<br>Remembered your password already?
						</a>
					</div>
					<img id="img" style="max-width: 100px; display: block; margin:0 auto; "  src="../photo/load-send.gif" >
					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn" style="width:200px; opacity:70%">
							<div class="login100-form-bgbtn"></div>
              <button id="submit-button" type="submit" class="login100-form-btn" >Retrieve</button>                                         
						</div>
					</div>

					<div class="flex-col-c p-t-50">
						<a href="register.php" style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 15px;">
                Sign up?
						</a>
					</div>
          </form>
          <?php
        } 
        ?>

			</div>
		</div>
	</div>

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

      $("#img").hide();
      $("#submit-button").on("click", function(){
          $("#img").show();
          $("#submit-button").hide();
      });
  </script>
  <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
  <script src="vendor/bootstrap/js/bootstrap-select-language.js" type="text/javascript"></script>
</body>
</html>
