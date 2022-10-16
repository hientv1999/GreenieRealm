<div class="login100-pic js-tilt" data-tilt>
    <img src="../photo/coming_soon.jpg" alt="IMG">
</div>
<form class="login100-form validate-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <span class="login100-form-title">
        <strong>Connect new device</strong>
        <p style = "font-size: 16px"> Setup your device </p>
        <p style="font-size: 14px; color: #fc8f8f; text-align: center;" ><?php echo "$msg" ?></p>
    </span>
    <div class="wrap-input100 validate-input" data-validate = "<?php echo "$otp_err"; ?>" >
        <input class="input100" type="text" name="otp" placeholder="OTP">
        <span class="focus-input100"></span>
        <span class="symbol-input100">
            <i class="fas fa-key" aria-hidden="true"></i>
        </span>
    </div>
    <div class="wrap-input100 validate-input" data-validate = "<?php echo $sensorName_err; ?>" >
        <input class="input100" type="text" name="sensorName" placeholder="Device Name">
        <span class="focus-input100"></span>
        <span class="symbol-input100">
            <i class="fas fa-microchip" aria-hidden="true"></i>
        </span>
    </div>
    <div class="wrap-input100 validate-input" data-validate = "<?php echo $location_err; ?>" >
        <input class="input100" type="text" name="location" placeholder="Location">
        <span class="focus-input100"></span>
        <span class="symbol-input100">
            <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
        </span>
    </div>
    <div class="container-login100-form-btn">
        <input id="submit-button" type="submit" value="Setup" class="login100-form-btn">
        <a class="btn btn-link" href="home.php">Cancel</a>
    </div>
    
</form>