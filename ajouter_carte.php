<?php 
include 'connexion.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Carte</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen  bg-purple-100">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Ajouter une Carte</h2>
        
        <form action="tretment_carte.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label for="nom" class="block text-gray-700 font-medium mb-1">Nom de la carte :</label>
                <input type="text"  name="nom" required 
                       class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="nom" class="block text-gray-700 font-medium mb-1">category :</label>
                <input type="text"  name="category" required 
                       class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="nom" class="block text-gray-700 font-medium mb-1">limite :</label>
                <input type="number" name="limite" step="0.01" placeholder="1500" required 
                       class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>


            <button type="submit" name="ajoute_carte" 
                    class="w-full bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-300">
                Ajouter la carte
            </button>
            <a href="tableborde.php" class="block text-center text-sm text-gray-600 mt-3">
                ← Retour au tableau de bord
            </a>
        </form>
    </div>

</body>
</html>
