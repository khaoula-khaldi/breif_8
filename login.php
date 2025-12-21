<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include 'connexion.php';
require 'vendor/autoload.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['nomcomplet'];
    
    if($user && password_verify($password, $user['password'])){

        // Générer OTP
        $otp = str_pad(rand(0,999999),6,'0',STR_PAD_LEFT);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_user_id'] = $user['id'];
        $_SESSION['otp_expiry'] = time() + 300; // valide 5 minutes

        // Envoi OTP par email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'khaoula2417@gmail.com';
            $mail->Password   = 'yzwcbctaahdtbgfc';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('khaoula2417@gmail.com', 'Smart Wallet');
            $mail->addAddress($user['email']);

            $mail->isHTML(true);
            $mail->Subject = 'Votre code OTP';
            $mail->Body    = "Votre code OTP est : <b>$otp</b>";
            $mail->AltBody = "Votre code OTP est : $otp";

            $mail->send();

            // Rediriger vers la page de vérification OTP
            header("Location: otp_verification.php");
            exit;

        } catch (Exception $e) {
            $_SESSION['error'] = "Erreur d'envoi de l'OTP: {$mail->ErrorInfo}";
            header("Location: login.php");
            exit;
        }

    } 
}
?>
