<?php
session_start();
include 'connexion.php';
require 'vendor/autoload.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($user && password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['nomcomplet'];

        // Vérifier si l'utilisateur a déjà une carte
        $stmt = $pdo->prepare("SELECT COUNT(*) AS nb_cartes FROM carte WHERE user_id = ?");
        $stmt->execute([$user['id']]);
        $nb_cartes = $stmt->fetch(PDO::FETCH_ASSOC)['nb_cartes'];

        if ($nb_cartes == 0) {
            header("Location: ajouter_carte.php");
            exit;
        } else {
            header("Location: tableborde.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Email ou mot de passe incorrect";
        header("Location: login.php");
        exit;
    }
}
?>

        
        // $otp = str_pad(rand(0,999999),6,'0',STR_PAD_LEFT);
        // $_SESSION['otp'] = $otp;
        // $_SESSION['otp_user_id'] = $user['id'];
        // $_SESSION['otp_expiry'] = time() + 300;

        
        
//         $mail = new PHPMailer(true);
//         try {
//             $mail->isSMTP();
//             $mail->Host       = 'smtp.gmail.com';
//             $mail->SMTPAuth   = true;
//             $mail->Username   = 'khaoula2417@gmail.com';
//             $mail->Password   = 'yzwcbctaahdtbgfc';
//             $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
//             $mail->Port       = 465;
            
//             $mail->setFrom('ton.email@gmail.com', 'Smart Wallet');
//             $mail->addAddress($_SESSION['username'] );

//             $mail->isHTML(true);
//             $mail->Subject = 'Votre code OTP';
//             $mail->Body    = "Votre code OTP est : <b>$otp</b>";
//             $mail->AltBody = "Votre code OTP est : $otp";
            
//             $mail->send();
            
            
//             header("Location: otp_verification.php");
//             exit;
            
//             echo "pass sent";
            
//         } catch (Exception $e) {
//             $_SESSION['error'] = "Erreur d'envoi de l'OTP: {$mail->ErrorInfo}";
//             header("Location: compte_deja.php");
//             exit;
//         }

//     } else {
//         $_SESSION['error'] = "Email ou mot de passe incorrect";
//         header("Location: compte_deja.php");
//         exit;
//     }
// }
?>





