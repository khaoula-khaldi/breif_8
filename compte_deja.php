<?php include 'connexion.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen bg-purple-100">
     <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Connecter a votre compte</h2>
    <p class="text-center text-gray-700 text-base mt-5">Tu n'avais pas un compte ?<a href="index.php" class="text-blue-500 font-bold hover:text-blue-700 hover:underline ml-1">S'inscrire</a></p>
    <form action="login.php" method="POST" class="space-y-4">
      <!-- Email -->
      <div>
        <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
        <input type="email" id="email" name="email" placeholder="exemple@email.com"
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
      <button type="submit" name="login" 
              class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition">
        se connecter
      </button>  
    </form>

    
  </div>
</body>
</html>

