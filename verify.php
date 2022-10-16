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
  $allow_login = false;
                      
  if(isset($_GET['username']) && !empty($_GET['username']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
      // Verify data
      $username = trim($_GET['username']); // Set username variable
      $hash = trim($_GET['hash']); // Set hash variable
      session_start();
      $_SESSION["username"] = $username;                     
      $sql = "SELECT hash FROM users WHERE username=? AND active=0"; 
      $result = $db_handle->runQuery($sql, "s", array($username));
      if (count($result) == 1){
        if ($hash == $result[0]["hash"]){
          $new_hash = md5( mt_rand(0,1000));
          $db_handle->runBaseQuery("UPDATE users SET active= 1, hash= '$new_hash' WHERE username='$username'");
          $msg = 'Your account has been activated, you can now login.';
          $allow_login = true;
        } else {
          $msg = "Invalid link";
        } 
      } else {
        $msg = 'Your account is not existed or you have already activated your account.';
      }
  }else{
      // Invalid approach
      $msg = 'Invalid approach, please use the link that has been send to your email.';
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
  <title>Greenie Realm - Verify</title>

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
    <p class="lead" style="font-family: Verdana, Geneva, Tahoma, sans-serif"><?php echo $msg; ?></p>
    </div>
  </section>

    <?php
    if ($allow_login == true){
    ?>
      <div class="limiter">
		    <div class="container-login100">
          <div class="container-login100-form-btn">
			      <div class="wrap-login100-form-btn">
				      <div class="login100-form-bgbtn"></div>
                <a href ="index.php">
                    <button class="login100-form-btn" >Login</button>   
                </a>                                      
			        
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
  </script>
  <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
  <script src="vendor/bootstrap/js/bootstrap-select-language.js" type="text/javascript"></script>
</body>
</html>
