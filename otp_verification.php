<?php

session_start();
include 'connexion.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vérification OTP</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-purple-100 flex items-center justify-center min-h-screen">

  <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-sm">
    <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Vérification OTP</h2>
    <form method="POST" action="otp_verification.php" class="space-y-4">
      <div>
        <label class="block text-gray-700 font-medium mb-2" for="otp_input">Entrez le code OTP :</label>
        <input type="text" name="otp_input" id="otp_input" required
          class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-purple-400" 
          placeholder="000000">
      </div>

      <button type="submit"
        class="w-full bg-purple-500 hover:bg-purple-600 text-white py-2 rounded-lg transition-colors font-semibold">
        Valider
      </button>
    </form>

    <p class="text-sm text-gray-500 mt-4 text-center">
      retour a connexion ? <a href="login.php" class="text-purple-500 hover:underline">retour</a>
    </p>
  </div>

</body>
</html>


<?php

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $code_otp = $_POST['otp_input'];
    if (!isset($_SESSION['otp'], $_SESSION['otp_user_id'], $_SESSION['otp_expiry'])) {
    $_SESSION['error'] = "Aucun code OTP trouvé. Connectez-vous de nouveau.";
    header("Location: login.php");
    exit;
    }
    if (time() > $_SESSION['otp_expiry']) {
    $_SESSION['error'] = "Le code OTP a expiré. Connectez-vous à nouveau.";
    header("Location: login.php");
    exit;
    }
    if($code_otp == $_SESSION['otp']){
        $user_id = $_SESSION['otp_user_id'];
        unset($_SESSION['otp'], $_SESSION['otp_user_id'], $_SESSION['otp_expiry']);
        $_SESSION['user_id']=$user_id;
        $_SESSION['username']=$user['nomcomplet'];

        $stmt = $pdo->prepare("SELECT COUNT(*) AS nb_cartes FROM carte WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $nb_cartes = $stmt->fetch(PDO::FETCH_ASSOC)['nb_cartes'];
        if ($nb_cartes == 0) {
            header("Location: ajouter_carte.php");
        } else {
            header("Location: tableborde.php");
        }
        exit;
    } else {
        $_SESSION['error'] = "Code OTP incorrect.";
    }
}

