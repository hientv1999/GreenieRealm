<?php
// Include config file
require_once "classes/Table.php";
$db_handle = new DBController();
// Check if remember me 
require_once "authCookieSessionValidate.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = $email = "";
$username_err = $password_err = $confirm_password_err = $email_err = "";
$msg = "";
$href = $_COOKIE["link"];
// Get user's browser language

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
      $param_username = trim($_POST["username"]);
      $sql = "SELECT id FROM users WHERE username = ? LIMIT 1";
      $result = $db_handle->runQuery($sql, "s", array($param_username));
      if (!empty($result)){
        $username_err = "This username is already taken.";
      } else {
        $username = trim($_POST["username"]);
      }
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
    
    // Validate email
    if (empty(trim($_POST["email"]))) {
      $email_err = "Email is required";
    } else {
      $email = trim($_POST["email"]);
      // check if e-mail address is well-formed
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format";
      }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, email, hash, language, created_at) VALUES (?, ?, ?, ?, ?, ?)";
        // Set parameters
        $param_username = $username;
        $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
        $param_email = $email;
        $param_hash = $hash = md5( mt_rand(0,1000));
        $param_language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        $param_created_at = gmdate('Y-m-d H:i:s');
        $db_handle->insert($sql, "ssssss", array($param_username, $param_password, $param_email, $param_hash, $param_language, $param_created_at));
        $db_handle->runBaseQuery("INSERT INTO settings (username) VALUES ('$username')");
        // Redirect to login page
        $output = shell_exec("python3 verification_mailing.py $username $email $hash $param_language $href");
        if ($output != NULL){
          ?>
          <script>alert("<?php echo $output?>");</script>
          <?php
        } else {
          $direct_to = explode("@", $email)[1];
          ?>
          <script>
            alert('A verification email has been sent to you. Following the instruction to activate your account!');
            window.location.href = "https://<?php echo $direct_to; ?>";
          </script>
          <?php
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
  <title>Greenie Realm - Register</title>

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

  
</head>
<body>
  <!-- Navigation -->
  <?php include "template/topbar_index.html" ?>
    
  <!-- Content section--> 
  <section class="py-5">
    <div class="container">
      <p class="lead" style="font-family: Verdana, Geneva, Tahoma, sans-serif">To be a telekinesis in the modern world </p>
    </div>
  </section>
  
  <!-- Login form section-->
  <div class="limiter"></div>
		<div class="container-login100">
			<div class="wrap-login100 p-l-55 p-r-55 p-t-54 p-b-54">
				<form class="login100-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<span class="login100-form-title p-b-49 ">
						<strong style="font-size: 30px">Sign Up</strong>
              <p style="font-family: Verdana, Geneva, Tahoma, sans-serif">Please fill this form to create an account.<br></p>
              <span style="font-size: 14px; color: #fc8f8f;" ><?php echo $msg; ?></span>
					</span>

					<div class="wrap-input100 m-b-23 <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>" >
						<span class="label-input100">Username</span>
            <span style="font-size: 14px; color: #fc8f8f;" ><?php echo $username_err; ?></span>
						<input class="input100" type="text" name="username" placeholder="Type your username" value="<?php echo $username; ?>">
						<span class="focus-input100" data-symbol="&#xf206;"></span>
					</div>

					<div class="wrap-input100 m-b-23 <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>" >
						<span class="label-input100">Password</span>
            <span style="font-size: 14px; color: #fc8f8f;" ><?php echo $password_err; ?></span>
						<input class="input100" type="password" name="password" placeholder="Type your password" value="<?php echo $password; ?>" id="pw">
						<span class="focus-input100" data-symbol="&#xf190;"></span>
					</div>

          <div class="wrap-input100 m-b-23 <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>" >
						<span class="label-input100">Confirm password</span>
            <span style="font-size: 14px; color: #fc8f8f;" ><?php echo $confirm_password_err; ?></span>
						<input class="input100" type="password" name="confirm_password" placeholder="Retype your password" value="<?php echo $confirm_password; ?>" id="pw2">
						<span class="focus-input100" data-symbol="&#xf190;"></span>
					</div>

          <div class="wrap-input100 m-b-23 <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>" >
						<span class="label-input100">Email</span>
            <span style="font-size: 14px; color: #fc8f8f;" ><?php echo $email_err; ?></span>
						<input class="input100" type="email" name="email" placeholder="Type your email" value="<?php echo $email; ?>">
						<span class="focus-input100" data-symbol="&#x2709;"></span>
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
						<a href="forget_password.php" style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 15px;">
							Forgot password?
						</a>
					</div>
          <img id="img" style="max-width: 100px;  margin:0 auto; display: none" src="../photo/load-send.gif" >
					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn" style="width:200px; opacity:70%" >
							<div class="login100-form-bgbtn"></div>
                <button id = "submit-button" type="submit" class="login100-form-btn" >Register</button>                                         
						</div>
					</div>

					<div class="flex-col-c p-t-50">
						<a href="index.php" style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size: 15px;">
              Have an account already?
						</a>
					</div>

				</form>
			</div>
		</div>
	</div>

  <!-- Footer -->
  <?php include "template/footbar_index.php" ?>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script> <!-- menu, lnaguage toggle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>  <!-- menu, lnaguage toggle -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" crossorigin="anonymous"></script> <!-- menu icon on mobile -->      
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

    $("#submit-button").on("click", function(){
      document.getElementById("img").style.display = "block";
      document.getElementById("submit-button").style.display = "none";
    });
  </script>
  <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
  <script src="vendor/bootstrap/js/bootstrap-select-language.js" type="text/javascript"></script>
</body>
</html>
