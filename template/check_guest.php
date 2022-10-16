<?php
if ($_SESSION["privilege"] == 0){
    $redirect_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . "/user/home.php";
    ?>
    <script>alert("You, as a \"Guest\", don't have permission to access this page");
        window.location.href = "<?php echo $redirect_link; ?>" ; 
    </script>
    <?php
}
?>