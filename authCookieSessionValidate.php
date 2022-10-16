<?php 
require_once "classes/Auth.php";
require_once "classes/Util.php";

$auth = new Auth();
$db_handle = new DBController();
$util = new Util();

// Get Current date, time
$current_time = time();
$current_date = date("Y-m-d H:i:s", $current_time);

// Set Cookie expiration for 10 years
$cookie_expiration_time = $current_time + (10 * 365 * 24 * 60 * 60);  // for 10 years

$isLoggedIn = false;

// Check if loggedin session and redirect if session exists
if (! empty($_SESSION["id"])) {
    $isLoggedIn = true;
}
// Check if loggedin session exists
else if (! empty($_COOKIE["member_login"]) && ! empty($_COOKIE["random_password"]) && ! empty($_COOKIE["random_selector"])) {
    // Initiate auth token verification diirective to false
    $isPasswordVerified = false;
    $isSelectorVerified = false;
    $isExpiryDateVerified = false;
    
    // Get token for username
    $userToken = $auth->getTokenByUsername($_COOKIE["member_login"]);
    
    // Validate random password cookie with database
    if (password_verify($_COOKIE["random_password"], $userToken[0]["password_hash"])) {
        $isPasswordVerified = true;
    }
    
    // Validate random selector cookie with database
    if (password_verify($_COOKIE["random_selector"], $userToken[0]["selector_hash"])) {
        $isSelectorVerified = true;
    }
    
    // check cookie expiration by date
    if($userToken[0]["expiry_date"] >= $current_date) {
        $isExpiryDateVerified = true;
    }
    
    // Redirect if all cookie based validation retuens true
    // Else, mark the token as expired and clear cookies
    if (!empty($userToken[0]["id"]) && $isPasswordVerified && $isSelectorVerified && $isExpiryDateVerified) {
        $isLoggedIn = true;
    } else {
        if(!empty($userToken[0]["id"])) {
            $auth->markAsExpired($userToken[0]["id"]);
        }
        // clear cookies
        $util->clearAuthCookie();
    }
}
// load data if already remember me
if ($isLoggedIn) {
    $username = $_COOKIE["member_login"];
    $user = $auth->getMemberByUsername($username);
    $_SESSION["loggedin"] = true;
    $_SESSION["id"] = $user[0]["id"];
    $_SESSION["username"] = $user[0]["username"]; 
    $_SESSION["language"] = $user[0]["language"];
    $_SESSION["privilege"] = $user[0]["privilege"];
    $_SESSION["timezone"] = $user[0]["timezone"];
    $_SESSION["date_structure"] = $user[0]["date_structure"];
    $_SESSION["setup_mode"] = "OTP";
    setcookie('googtrans', '/en/'. $language, $cookie_expiration_time, '');
    $util->redirect("user/home.php");
  }
?>