<?php
if ($_SESSION["privilege"] != 2){
    $redirect_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . "/user/home.php";
    ?>
    <script>
        alert("You must have admin privilege to access this feature");
        window.location.href = "<?php echo $redirect_link; ?>" ; 
    </script>
    <?php
}
?>