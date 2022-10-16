<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
include "../../template/check_alive.php";

?>
 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?php echo htmlspecialchars($_SESSION["username"]); ?> - Setting</title> 
        <style>
        a, a:link, a:visited {
            color: #48486a;
            transition: color 1s;
            transition-timing-function: ease;
        }
        a:hover, a:active, a:focus {
            color: #9595b7;
            opacity: 1;
        }
        </style>
        <link href="../../photo/favicon.png" rel="icon">
        <link href="../../css/styles.css" rel="stylesheet"> <!-- css for navbar -->
        <!-- Bootstrap core CSS -->
        <link href="../../vendor/bootstrap/css/bootstrap-home.css" rel="stylesheet" type="text/css"> <!-- main font -->
        <link href="../../vendor/bootstrap/css/bootstrap-select-language.css" rel="stylesheet" type="text/css"> language dropout menu
            <link href="../../css/flag-icon.css" rel="stylesheet" type="text/css"> <!--country flags -->
        <!-- <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous"> -->
        
    </head>
    <body class="sb-nav-fixed">
    <?php include "../../template/topbar_setting.html" ?>
        <div id="layoutSidenav">
            <?php include "../../template/navbar_setting.html" ?>
            <div id="layoutSidenav_content">
                <main>
                <div class="container-fluid">
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Privacy</li>
                    </ol>
                    <div class="container-fluid">
                        <div style = "text-align: center; font-size: 12px">
                            <a href = "email.php"> <strong>Email</strong> </a>                        
                        </div>
                        <br>
                        <div style = "text-align: center; font-size: 12px">
                            <a  href = "username.php"> <strong>Username</strong> </a>
                        </div>
                        <br>
                        <div style = "text-align: center; font-size: 12px">
                        <a  href = "password.php"> <strong>Password</strong> </a>
                        </div>
                        <br>
                        <div style = "text-align: center; font-size: 12px">
                        <a  href = "language.php"> <strong>Language</strong> </a>
                        </div>
                    </div>
                    <br>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Visualization</li>
                    </ol>
                    <div class="container-fluid">
                        <div style = "text-align: center; font-size: 12px">
                            <a  href = "graph.php"> <strong>Graphs</strong> </a>           
                        </div>
                        <br>
                        <div style = "text-align: center; font-size: 12px">
                        <a  href = "date_structure.php"> <strong>Date Format</strong> </a>   
                        </div>
                        <br>
                        <div style = "text-align: center; font-size: 12px">  
                            <a  href = "timezone.php"> <strong>Timezone</strong> </a>  
                        </div>
                    </div>
                </div>
                </main>
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
        <script src="jquery.js" type="text/javascript"></script>
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
        </script>
        <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>
        <script src="../../vendor/bootstrap/js/bootstrap-select-language.js" type="text/javascript"></script>
    </body>
</html>