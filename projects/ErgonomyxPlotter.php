<?php
// Initialize the session
session_start();
$_SESSION["ergonomyx_login"] = 0;
$password = "";
$password_err = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
  // Check if password is empty
  if(empty(trim($_POST["password"]))){
    $password_err = "Please enter your password.";
  } else{
      $password = trim($_POST["password"]);
      if ($password != "sitstandmove"){
        $password_err = "Wrong password";
      }
  }
  // Validate credentials and verified account
  if(empty($password_err)){
    $_SESSION["ergonomyx_login"] = 1;
  } else {
    $_SESSION["ergonomyx_login"] = 0;
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
  
  <title>Ergonomyx Plotter</title>
  <!-- Favicons -->
  <link href="../photo/favicon.png" rel="icon">

  <!-- Custom styles for this template -->
  <link href="../css/full-width-pics.css" rel="stylesheet">
  <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../fonts/iconic/css/material-design-iconic-font.min.css">
  <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../css/util.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
  <link rel="stylesheet" type="text/css" href="../css/styles.css">
  <link href="../css/style.css" type="text/css" rel="stylesheet"> <!-- button css -->
  <style>
    .btn-group1 button {
      align-self: center;
      background-color: #fff;
      background-image: none;
      background-position: 0 90%;
      background-repeat: repeat no-repeat;
      background-size: 4px 3px;
      border-radius: 15px 225px 255px 15px 15px 255px 225px 15px;
      border-style: solid;
      border-width: 2px;
      box-shadow: rgba(0, 0, 0, .2) 15px 28px 25px -18px;
      box-sizing: border-box;
      color: #41403e;
      cursor: pointer;
      display: inline-block;
      font-family: Neucha, sans-serif;
      font-size: 1rem;
      line-height: 23px;
      outline: none;
      padding: .75rem;
      text-decoration: none;
      transition: all 235ms ease-in-out;
      border-bottom-left-radius: 15px 255px;
      border-bottom-right-radius: 225px 15px;
      border-top-left-radius: 255px 15px;
      border-top-right-radius: 15px 225px;
      user-select: none;
      -webkit-user-select: none;
      touch-action: manipulation;
      padding: 10px 24px; /* Some padding */
      cursor: pointer; /* Pointer/hand icon */
      float: left; /* Float the buttons side by side */
    }

    .btn-group1 button:not(:last-child) {
      border-right: none; /* Prevent double borders */
    }

    /* Clear floats (clearfix hack) */
    .btn-group1:after {
      content: "";
      clear: both;
      display: table;
    }

    /* Add a background color on hover */
    .btn-group1 button:hover  {
      box-shadow: rgba(0, 0, 0, .3) 2px 8px 8px -5px;
      transform: translate3d(0, 5px, 0);
    }

    .btn-group1 button:focus  {
      box-shadow: rgba(0, 0, 0, .3) 2px 8px 8px -5px;
      transform: translate3d(0, 5px, 0);
    }
</style>


  <link href="../vendor/bootstrap/css/bootstrap-mainpage.css" rel="stylesheet" type="text/css" />
  <link href="../vendor/bootstrap/css/bootstrap-select-language.css" rel="stylesheet" type="text/css" />
  <link href="../css/flag-icon.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" href="../css/effect1.css" />
  <script src="../vendor/bootstrap/js_preloading/modernizr.custom.js"></script>
  
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container" style="position:relative">
        <a class="navbar-brand" href="../index.php"></a>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" style="font-family: Verdana, Geneva, Tahoma, sans-serif" href="../index.php">Home Control</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" style="font-family: Verdana, Geneva, Tahoma, sans-serif" href="../projects.php">Projects
                    <span class="sr-only">(current)</span>
                    </a>
                </li>
            </ul>    
        <div class = " nav-item ml-auto" style = "margin-top: 8px; position: relative; text-align: center;" >
        <!--Google Translate API-->
        <div id="google_translate_element" style="display: none;"></div>
        <select class="selectpicker"  data-width="fit"  onchange="translateLanguage(this.value);">
            <option selected disabled hidden style="display:none;">Language</option>
            <option data-content='<span class="flag-icon flag-icon-af"></span> Afrikaans' value="Afrikaans">Afrikaans</option>
            <option  data-content='<span class="flag-icon flag-icon-al"></span> Albanian' value="Albanian">Albanian</option>
            <option  data-content='<span class="flag-icon flag-icon-ar"></span> Arabic' value="Arabic">Arabic</option>
            <option  data-content='<span class="flag-icon flag-icon-am"></span> Armenian' value="Armenian">Armenian</option>
            <option  data-content='<span class="flag-icon flag-icon-az"></span> Azerbaijani' value="Azerbaijani">Azerbaijani</option>
            <option  data-content='<span class="flag-icon flag-icon-eu"></span> Basque' value="Basque">Basque</option>
            <option  data-content='<span class="flag-icon flag-icon-be"></span> Belarusian' value="Belarusian">Belarusian</option>
            <option  data-content='<span class="flag-icon flag-icon-bn"></span> Bengali' value="Bengali">Bengali</option>
            <option  data-content='<span class="flag-icon flag-icon-bs"></span> Bosnian' value="Bosnian">Bosnian</option>
            <option  data-content='<span class="flag-icon flag-icon-bg"></span> Bulgarian' value="Bulgarian">Bulgarian</option>
            <option  data-content='<span class="flag-icon flag-icon-cu"></span> Catalan' value="Catalan">Catalan</option>
            <option  data-content='<span class="flag-icon flag-icon-cn"></span> Chinese (Simplified)' value="Chinese (Simplified)">Chinese (Simplified)</option>
            <option  data-content='<span class="flag-icon flag-icon-tw"></span> Chinese (Traditional)' value="Chinese (Traditional)">Chinese (Traditional)</option>
            <option  data-content='<span class="flag-icon flag-icon-co"></span> Corsican' value="Corsican">Corsican</option>
            <option  data-content='<span class="flag-icon flag-icon-hr"></span> Croatian' value="Croatian">Croatian</option>
            <option  data-content='<span class="flag-icon flag-icon-cz"></span> Czech' value="Czech">Czech</option>
            <option  data-content='<span class="flag-icon flag-icon-dk"></span> Danish' value="Danish">Danish</option>
            <option  data-content='<span class="flag-icon flag-icon-nl"></span> Dutch' value="Dutch">Dutch</option>
            <option  data-content='<span class="flag-icon flag-icon-us"></span> English' value="English">English</option>
            <option  data-content='<span class="flag-icon flag-icon-et"></span> Estonian' value="Estonian">Estonian</option>
            <option  data-content='<span class="flag-icon flag-icon-fi"></span> Finnish' value="Finnish">Finnish</option>
            <option  data-content='<span class="flag-icon flag-icon-fr"></span> French' value="French">French</option>
            <option  data-content='<span class="flag-icon flag-icon-gl"></span> Galician' value="Galician">Galician</option>
            <option  data-content='<span class="flag-icon flag-icon-ge"></span> Georgian' value="Georgian">Georgian</option>
            <option  data-content='<span class="flag-icon flag-icon-de"></span> German' value="German">German</option>
            <option  data-content='<span class="flag-icon flag-icon-gr"></span> Greek' value="Greek">Greek</option>
            <option  data-content='<span class="flag-icon flag-icon-gu"></span> Gujarati' value="Gujarati">Gujarati</option>
            <option  data-content='<span class="flag-icon flag-icon-ht"></span> Haitian Creole' value="Haitian Creole">Haitian Creole</option>
            <option  data-content='<span class="flag-icon flag-icon-il"></span> Hebrew' value="Hebrew">Hebrew</option>
            <option  data-content='<span class="flag-icon flag-icon-in"></span> Hindi' value="Hindi">Hindi</option>
            <option  data-content='<span class="flag-icon flag-icon-hu"></span> Hungarian' value="Hungarian">Hungarian</option>
            <option  data-content='<span class="flag-icon flag-icon-is"></span> Icelandic' value="Icelandic">Icelandic</option>
            <option  data-content='<span class="flag-icon flag-icon-id"></span> Indonesian' value="Indonesian">Indonesian</option>
            <option  data-content='<span class="flag-icon flag-icon-ga"></span> Irish' value="Irish">Irish</option>
            <option  data-content='<span class="flag-icon flag-icon-it"></span> Italian' value="Italian">Italian</option>
            <option  data-content='<span class="flag-icon flag-icon-jp"></span> Japanese' value="Japanese">Japanese</option>
            <option  data-content='<span class="flag-icon flag-icon-kn"></span> Kannada' value="Kannada">Kannada</option>
            <option  data-content='<span class="flag-icon flag-icon-kz"></span> Kazakh' value="Kazakh">Kazakh</option>
            <option  data-content='<span class="flag-icon flag-icon-km"></span> Khmer' value="Khmer">Khmer</option>
            <option  data-content='<span class="flag-icon flag-icon-rw"></span> Kinyarwanda' value="Kinyarwanda">Kinyarwanda</option>
            <option  data-content='<span class="flag-icon flag-icon-kr"></span> Korean' value="Korean">Korean</option>
            <option  data-content='<span class="flag-icon flag-icon-ir"></span> Kurdish' value="Kurdish (Kurmanji)">Kurdish</option>
            <option  data-content='<span class="flag-icon flag-icon-ky"></span> Kyrgyz' value="Kyrgyz">Kyrgyz</option>
            <option  data-content='<span class="flag-icon flag-icon-la"></span> Lao' value="Lao">Lao</option>
            <option  data-content='<span class="flag-icon flag-icon-lv"></span> Latvian' value="Latvian">Latvian</option>
            <option  data-content='<span class="flag-icon flag-icon-lt"></span> Lithuanian' value="Lithuanian">Lithuanian</option>
            <option  data-content='<span class="flag-icon flag-icon-lb"></span> Luxembourgish' value="Luxembourgish">Luxembourgish</option>
            <option  data-content='<span class="flag-icon flag-icon-mk"></span> Macedonian' value="Macedonian">Macedonian</option>
            <option  data-content='<span class="flag-icon flag-icon-mg"></span> Malagasy' value="Malagasy">Malagasy</option>
            <option  data-content='<span class="flag-icon flag-icon-ms"></span> Malay' value="Malay">Malay</option>
            <option  data-content='<span class="flag-icon flag-icon-ml"></span> Malayalam' value="Malayalam">Malayalam</option>
            <option  data-content='<span class="flag-icon flag-icon-mt"></span> Maltese' value="Maltese">Maltese</option>
            <option  data-content='<span class="flag-icon flag-icon-mr"></span> Marathi' value="Marathi">Marathi</option>
            <option  data-content='<span class="flag-icon flag-icon-mn"></span> Mongolian' value="Mongolian">Mongolian</option>
            <option  data-content='<span class="flag-icon flag-icon-my"></span> Myanmar (Burmese)' value="Myanmar (Burmese)">Myanmar (Burmese)</option>
            <option  data-content='<span class="flag-icon flag-icon-ne"></span> Nepali' value="Nepali">Nepali</option>
            <option  data-content='<span class="flag-icon flag-icon-no"></span> Norwegian' value="Norwegian">Norwegian</option>
            <option  data-content='<span class="flag-icon flag-icon-mw"></span> Nyanja (Chichewa)' value="Nyanja (Chichewa)">Nyanja (Chichewa)</option>
            <option  data-content='<span class="flag-icon flag-icon-ps"></span> Pashto' value="Pashto">Pashto</option>
            <option  data-content='<span class="flag-icon flag-icon-pl"></span> Polish' value="Polish">Polish</option>
            <option  data-content='<span class="flag-icon flag-icon-pt"></span> Portuguese (Portugal, Brazil)' value="Portuguese (Portugal, Brazil)">Portuguese (Portugal, Brazil)</option>
            <option  data-content='<span class="flag-icon flag-icon-pa"></span> Punjabi' value="Punjabi">Punjabi</option>
            <option  data-content='<span class="flag-icon flag-icon-ro"></span> Romanian' value="Romanian">Romanian</option>
            <option  data-content='<span class="flag-icon flag-icon-ru"></span> Russian' value="Russian">Russian</option>
            <option  data-content='<span class="flag-icon flag-icon-sm"></span> Samoan' value="Samoan">Samoan</option>
            <option  data-content='<span class="flag-icon flag-icon-gd"></span> Scots Gaelic' value="Scots Gaelic">Scots Gaelic</option>
            <option  data-content='<span class="flag-icon flag-icon-sr"></span> Serbian' value="Serbian">Serbian</option>
            <option  data-content='<span class="flag-icon flag-icon-st"></span> Sesotho' value="Sesotho">Sesotho</option>
            <option  data-content='<span class="flag-icon flag-icon-sn"></span> Shona' value="Shona">Shona</option>
            <option  data-content='<span class="flag-icon flag-icon-sd"></span> Sindhi' value="Sindhi">Sindhi</option>
            <option  data-content='<span class="flag-icon flag-icon-si"></span> Sinhala (Sinhalese)' value="Sinhala (Sinhalese)">Sinhala (Sinhalese)</option>
            <option  data-content='<span class="flag-icon flag-icon-sk"></span> Slovak' value="Slovak">Slovak</option>
            <option  data-content='<span class="flag-icon flag-icon-sl"></span> Slovenian' value="Slovenian">Slovenian</option>
            <option  data-content='<span class="flag-icon flag-icon-so"></span> Somali' value="Somali">Somali</option>
            <option  data-content='<span class="flag-icon flag-icon-es"></span> Spanish' value="Spanish">Spanish</option>
            <option  data-content='<span class="flag-icon flag-icon-sv"></span> Swedish' value="Swedish">Swedish</option>
            <option  data-content='<span class="flag-icon flag-icon-tl"></span> Tagalog (Filipino)' value="Tagalog (Filipino)">Tagalog (Filipino)</option>
            <option  data-content='<span class="flag-icon flag-icon-tg"></span> Tajik' value="Tajik">Tajik</option>
            <option  data-content='<span class="flag-icon flag-icon-tt"></span> Tatar' value="Tatar">Tatar</option>
            <option  data-content='<span class="flag-icon flag-icon-th"></span> Thai' value="Thai">Thai</option>
            <option  data-content='<span class="flag-icon flag-icon-tr"></span> Turkish' value="Turkish">Turkish</option>
            <option  data-content='<span class="flag-icon flag-icon-tk"></span> Turkmen' value="Turkmen">Turkmen</option>
            <option  data-content='<span class="flag-icon flag-icon-ua"></span> Ukrainian' value="Ukrainian">Ukrainian</option>
            <option  data-content='<span class="flag-icon flag-icon-pk"></span> Urdu' value="Urdu">Urdu</option>
            <option  data-content='<span class="flag-icon flag-icon-ug"></span> Uyghur' value="Uyghur">Uyghur</option>
            <option  data-content='<span class="flag-icon flag-icon-uz"></span> Uzbek' value="Uzbek">Uzbek</option>
            <option  data-content='<span class="flag-icon flag-icon-vn"></span> Vietnamese' value="Vietnamese">Vietnamese</option>
            <option  data-content='<span class="flag-icon flag-icon-cy"></span> Welsh' value="Welsh">Welsh</option>
            <option  data-content='<span class="flag-icon flag-icon-zw"></span> Xhosa' value="Xhosa">Xhosa</option>
        </select>
        </div>
      </div>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
      </button> 
      </div>
  </nav>
  <?php
    if ($_SESSION["ergonomyx_login"] == 1){
  ?>
      <p id = "description" style ="text-align: center; position: absolute; width:80%; top: 50%; left: 50%; transform: translate(-50%, -50%);  position: absolute; font-size: 30px;"> Choose how long you want your chart to be below </p>
      <img id="loading" style="max-width: 100px;  top: 50%; left: 50%; transform: translate(-50%, -50%); position: absolute; margin:0 auto; display: none"  src="../photo/load-send.gif" >
      <canvas id="LineChart" style="top: 45%; left: 50%; transform: translate(-50%, -50%);  position: absolute; width: 100%; height: 70%;"></canvas>
      <div style="position: fixed; bottom: 90px; left: calc(50vw - 140px); width:100%;"class="btn-group1">
        <button id="Month" >Month</button>
        <button id="Quarter" >Quarter</button>
        <button id="Year" >Year</button>
      </div>
  <?php
    } else {
  ?>
      <div class="limiter" style="top: 50%; left: 50%; transform: translate(-50%, -50%); position: absolute;">
        <div class="container-login100">
          <div  class="wrap-login100 p-l-55 p-r-55 p-t-54 p-b-54">
            <form class="login100-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
              <span class="login100-form-title p-b-49 ">
                <strong style="font-size: 30px">Ergonomyx's Plotter<br></strong>
                <p style="font-family: Verdana, Geneva, Tahoma, sans-serif">Let's check it out <br></p>
              </span>

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
                  <h1 style ="font-size: 15px; display: inline-block; vertical-align: middle">Show password</h1>
              </label>
              
              </div>
              <div class="container-login100-form-btn">
                <div class="wrap-login100-form-btn" style="width:200px; opacity:70%">
                  <div class="login100-form-bgbtn"></div>
                  <button id = "submit-button" type="submit" class="login100-form-btn" >Login</button>                                         
                </div>
              </div>
            </form>
			  </div>
		  </div>
    </div>
  <?php
    }
  ?>
  
  

  <!-- Footer -->
  <footer class="py-4 bg-dark" style="position: fixed;  bottom: 0; width: 100%; ">
    <div class="container">
      <p class="m-0 text-center text-white" style="font-family: Verdana, Geneva, Tahoma, sans-serif">Copyright &copy; <?php echo date("Y") ?></p>
    </div>
  </footer>

    
  <script src = "../vendor/jquery/user_data.js"></script>  <!-- get users' weather, location -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script> <!-- menu, language toggle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>  <!-- menu, language toggle -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script> <!-- menu icon on mobile -->
  <script src="../vendor/jquery/scripts.js" type="text/javascript"></script> <!-- get curren year, highglight current tab -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js" crossorigin="anonymous"></script>
  <script src="ErgonomyxPlotter.js"></script>
  <script src="../jquery.js" type="text/javascript"></script> <!--the chart-->
  <script src="../bootstrap-2.js" type="text/javascript"></script> <!--allow menu toggle on mobile-->
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
  <script src="../vendor/bootstrap/js/bootstrap-select-language.js" type="text/javascript"></script>
</body>
</html>





