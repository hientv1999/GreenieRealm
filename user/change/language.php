<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect to login page
include "../../template/check_alive.php";
 
// Include config file
require_once "../../classes/Table.php";
$db_handle = new DBController();
require "functions/language_interpret.php";
// Define variables and initialize with empty values
$language= "";
$language_err = "";
$current_user = $_SESSION["username"];
$language_abbr = $_SESSION["language"];



// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["language"]))){
        $language_err = "Please select a default language you prefer.";
    } else {
        $language = encode(trim($_POST["language"]));
        if ($_SESSION["language"] == $language){
            $language_err = "Please select a default language that is different from the current one";
        }
    }
    
    if (empty($language_err)){
        $current_time = time();
        $current_date = date("Y-m-d H:i:s", $current_time);
        $cookie_expiration_time = $current_time + (10 * 365 * 24 * 60 * 60);  // for 10 years
        setcookie('googtrans', '/en/'. $language, $cookie_expiration_time, '/');
        $db_handle->runBaseQuery("UPDATE users SET language = '$language' WHERE username = '$current_user'");
        $_SESSION["language"] = $language;
        header("location: setting.php");
        exit();
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
        <title><?php echo htmlspecialchars($_SESSION["username"]); ?> - Change Language</title> 
        <link href="../../photo/favicon.png" rel="icon">
        <link href="../../css/styles.css" rel="stylesheet" />
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
                            <h2 style="text-align: center" >Change Language</h2>
                            <p style="text-align: center; color: rgb(230,92,92)">Your current default language is <?php echo decode($language_abbr) ?>.</p>
                            <p style="text-align: center" >Please select your default language. </p>   
                            <p style="font-size: 14px; color: #fc8f8f; text-align: center;" ><?php echo $language_err ?>
                                <br>
                                <select class="selectpicker"  style="align-items: center" data-width="fit" name = "language">
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
                            </p>  
                            <br>
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