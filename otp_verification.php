<?php
session_start();
if(!isset($_SESSION['otp'])){
    header('Location: login.php');
    exit;
}
?>
<form method="POST">
    <input type="text" name="otp" placeholder="Enter OTP" required>
    <button type="submit">Verifier</button>
</form>
<?php 
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $otp_input = trim($_POST['otp']);

    if($otp_input === $_SESSION['otp'] && time() < $_SESSION['otp_expiry']){
        unset($_SESSION['otp']);
        unset($_SESSION['otp_user_id']);
        unset($_SESSION['otp_expiry']);

        header('Location: tableborde.php');
        exit;
    } else {
        $error = "OTP invalide ou expiré ❌";
    }
}

if($error) echo "<p>$error</p>";
 ?>

