<?php 
include 'connexion.php';

session_start();

if(!isset($_SESSION['email_erreur'])){
    $_SESSION['email_erreur']=[];
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Créer un compte</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen bg-purple-100">

  <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Créer un compte</h2>
    <form action="sing_up.php" method="POST" class="space-y-4">
      <!-- Nom complet -->
      <div>
        <label class="block text-gray-700 font-medium mb-1">Nom complet</label>
         
        <input type="text" id="nomcomplet" name="nomcomplet" placeholder="Votre nom complet"
               required
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>
      <!-- Email -->
      <div>
        <label class="block text-gray-700 font-medium mb-1">Email</label>
        <input type="email" id="email" name="email"placeholder="exemple@email.com"
               required
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>
      <!-- Mot de passe -->
      <div>
        <label for="password" class="block text-gray-700 font-medium mb-1">Mot de passe</label>
        <input type="password" id="password" name="password" placeholder="Mot de passe"
               required
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
      </div>
      <!-- Bouton submit -->
      <button type="submit" name="inscrire" 
              class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition">
        S'inscrire
      </button> 
    </form>
    <p class="text-gray-500 text-sm mt-4 text-center">
      Déjà un compte ? <a href="compte_deja.php" class="text-blue-500 hover:underline">Se connecter</a>
    </p>

  </div>

 

</body>
</html>
